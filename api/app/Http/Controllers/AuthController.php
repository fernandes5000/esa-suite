<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $userRole = Role::where('name', 'user')->where('guard_name', 'sanctum')->first();
        if ($userRole) {
            $user->assignRole($userRole);
        }

        $token = $user->createToken('api')->plainTextToken;
        $roles = $user->getRoleNames();

        return $this->apiSuccess([
            'user' => $user,
            'token' => $token,
            'roles' => $roles,
            'user_id' => $user->id,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $roles = $user->getRoleNames();

        return $this->apiSuccess([
            'user' => $user,
            'token' => $user->createToken('api')->plainTextToken,
            'roles' => $roles,
            'user_id' => $user->id,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->apiSuccess(['message' => 'Logged out']);
    }
}