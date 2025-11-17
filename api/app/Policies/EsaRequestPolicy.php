<?php

namespace App\Policies;

use App\Models\EsaRequest;
use App\Models\User;

class EsaRequestPolicy
{
    public function create(User $user): bool
    {
        return $user->can('requests.create');
    }

    public function view(User $user, EsaRequest $esaRequest): bool
    {
        return $user->can('requests.view.own') && $user->id === $esaRequest->user_id;
    }

    public function update(User $user, EsaRequest $esaRequest): bool
    {
        return $user->id === $esaRequest->user_id && $esaRequest->status === 'draft';
    }
}