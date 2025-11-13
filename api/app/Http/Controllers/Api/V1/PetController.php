<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $pets = Pet::where('user_id', $request->user()->id)->get();

        return response()->json([
            'ok' => true,
            'data' => $pets,
        ]);
    }

    public function store(StorePetRequest $request)
    {
        $pet = Pet::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'ok' => true,
            'data' => $pet,
        ], 201);
    }

    public function show(Pet $pet)
    {
        $this->authorize('view', $pet);

        return response()->json([
            'ok' => true,
            'data' => $pet,
        ]);
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $this->authorize('update', $pet);

        $pet->update($request->validated());

        return response()->json([
            'ok' => true,
            'data' => $pet,
        ]);
    }

    public function destroy(Pet $pet)
    {
        $this->authorize('delete', $pet);

        $pet->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Pet deleted successfully',
        ]);
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

        return response()->json([
            'ok' => true,
            'data' => [
                'photo_url' => $pet->photo_url,
            ],
        ]);
    }
}
