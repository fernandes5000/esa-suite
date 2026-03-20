<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Pet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\PetResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PetController extends Controller
{
    use ApiResponseTrait, AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('create', Pet::class);

        $pets = Pet::where('user_id', $request->user()->id)->get();
        return $this->apiSuccess(PetResource::collection($pets));
    }

    public function store(StorePetRequest $request)
    {
        $this->authorize('create', Pet::class);

        $pet = Pet::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return $this->apiSuccess(new PetResource($pet), 201);
    }

    public function show(Pet $pet)
    {
        $this->authorize('view', $pet);
        return $this->apiSuccess(new PetResource($pet));
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $this->authorize('update', $pet);
        $pet->update($request->validated());
        return $this->apiSuccess(new PetResource($pet));
    }

    public function destroy(Pet $pet)
    {
        $this->authorize('delete', $pet);
        $pet->delete();
        return $this->apiSuccess(['message' => 'Pet deleted successfully']);
    }

    public function uploadPhoto(Request $request, Pet $pet)
    {
        $this->authorize('update', $pet);

        $request->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        if ($pet->photo_path) {
            Storage::disk('public')->delete($pet->photo_path);
        }

        $path = $request->file('photo')->store('pets', 'public');

        $pet->update(['photo_path' => $path]);

        return $this->apiSuccess(new PetResource($pet));
    }
}