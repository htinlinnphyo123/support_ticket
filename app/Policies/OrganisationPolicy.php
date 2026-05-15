<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;

class OrganisationPolicy
{
    /**
     * Perform pre-authorization checks globally for the Organisation model.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->type->value === 'agent') {
            return true;
        }

        return false; // Deny entirely if not an agent
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organisation $organisation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organisation $organisation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organisation $organisation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Organisation $organisation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organisation $organisation): bool
    {
        return false;
    }
}
