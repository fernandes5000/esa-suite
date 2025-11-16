<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->toIso8601String(),
            
            'is_banned' => $this->banned_at !== null,
            'banned_at' => $this->banned_at,

            'roles' => $this->whenLoaded('roles', function () {
                return $this->getRoleNames();
            }),
        ];
    }
}