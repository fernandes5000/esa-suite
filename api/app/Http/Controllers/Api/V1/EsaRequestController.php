<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EsaRequestResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\EsaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class EsaRequestController extends Controller
{
    use ApiResponseTrait, \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function getActiveOrCreateRequest(Request $request)
    {
        $user = $request->user();

        $activeRequest = EsaRequest::where('user_id', $user->id)
            ->whereIn('status', ['draft', 'pending', 'reviewing', 'approved'])
            ->with('pets')
            ->first();

        if ($activeRequest) {
            $this->authorize('view', $activeRequest);
            return $this->apiSuccess(new EsaRequestResource($activeRequest));
        }

        if (Gate::denies('create', EsaRequest::class)) {
            return $this->apiError('You are not authorized to create new requests.', 403);
        }

        $newRequest = EsaRequest::create([
            'user_id' => $user->id,
            'status' => 'draft',
            'wizard_step' => 1,
            'certificate_name' => $user->name,
        ]);

        return $this->apiSuccess(new EsaRequestResource($newRequest), 201);
    }

    public function update(Request $request, EsaRequest $esaRequest)
    {
        $this->authorize('update', $esaRequest);

        $validated = $request->validate([
            'wizard_step' => ['sometimes', 'integer', 'min:1'],
            'certificate_name' => ['sometimes', 'string', 'max:255'],
            'problem_checkboxes' => ['sometimes', 'array'],
            'description' => ['sometimes', 'string', 'max:5000'],
            'terms_accepted_at' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'string', Rule::in(['draft', 'pending'])],
        ]);

        $esaRequest->update($validated);
        $esaRequest->load('pets');

        return $this->apiSuccess(new EsaRequestResource($esaRequest));
    }

    public function syncPets(EsaRequest $esaRequest, Request $request)
    {
        $petIds = $request->input('pet_ids', []);
        
        $esaRequest->pets()->sync($petIds); 

        $esaRequest->load('pets');
        return $this->apiSuccess(new EsaRequestResource($esaRequest));
    }
}