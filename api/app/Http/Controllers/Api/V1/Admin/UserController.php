<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->paginate($request->integer('per_page', 50));

        return $this->apiSuccess(UserResource::collection($users)->response()->getData(true));
    }

    /**
     * Display the specified user and all available roles.
     * This is for populating the edit form.
     */
    public function show(User $user)
    {
        $user->load('roles');
        
        $allRoles = Role::where('guard_name', 'sanctum')->pluck('name');

        return $this->apiSuccess([
            'user' => new UserResource($user),
            'all_roles' => $allRoles,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'roles' => ['required', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')->where('guard_name', 'sanctum')],
            'is_banned' => ['required', 'boolean'],
        ]);

        $adminUser = $request->user();

        $newRoles = $validated['roles'];
        if ($user->id === $adminUser->id && $user->getRoleNames()->diff($newRoles)->isNotEmpty()) {
            return $this->apiError(__('You cannot change your own roles.'), 403);
        }

        $isBanned = (bool) $validated['is_banned'];
        if ($user->id === $adminUser->id && $isBanned) {
            return $this->apiError(__('You cannot ban yourself.'), 403);
        }

        DB::transaction(function () use ($user, $validated, $newRoles, $isBanned) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            if ($isBanned && !$user->banned_at) {
                $user->banned_at = now();
            } elseif (!$isBanned && $user->banned_at) {
                $user->banned_at = null;
            }

            $user->save();
            $user->syncRoles($newRoles);
        });

        $user->load('roles');
        return $this->apiSuccess(new UserResource($user));
    }
}