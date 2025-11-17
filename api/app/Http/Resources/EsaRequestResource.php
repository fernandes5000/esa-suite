<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EsaRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'wizard_step' => $this->wizard_step,
            'certificate_name' => $this->certificate_name,
            'problem_checkboxes' => $this->problem_checkboxes,
            'description' => $this->description,
            'terms_accepted_at' => $this->terms_accepted_at,
            'status' => $this->status,
            'fee_cents' => $this->fee_cents,
            'pets' => PetResource::collection($this->whenLoaded('pets')),
        ];
    }
}