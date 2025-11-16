<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pet $pet): bool
    {
        // The user can view the pet if they have the permission AND they own it.
        return $user->can('pets.manage') && $pet->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pet $pet): bool
    {
        // The user can update the pet if they have the permission AND they own it.
        return $user->can('pets.manage') && $pet->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pet $pet): bool
    {
        // The user can delete the pet if they have the permission AND they own it.
        return $user->can('pets.manage') && $pet->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     * This isn't tied to a specific pet, just the user model.
     */
    public function create(User $user): bool
    {
        return $user->can('pets.manage');
    }
}