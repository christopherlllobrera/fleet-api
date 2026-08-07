<?php

namespace App\Policies;

use App\Models\TollFare;
use App\Models\User;

class TollFarePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-toll-fare');
    }

    public function view(User $user, TollFare $tollFare): bool
    {
        return $user->can('view-toll-fare');
    }

    public function create(User $user): bool
    {
        return $user->can('create-toll-fare');
    }

    public function update(User $user, TollFare $tollFare): bool
    {
        return $user->can('edit-toll-fare');
    }

    public function delete(User $user, TollFare $tollFare): bool
    {
        return $user->can('delete-toll-fare');
    }

    public function restore(User $user, TollFare $tollFare): bool
    {
        return $user->can('restore-toll-fare');
    }

    public function forceDelete(User $user, TollFare $tollFare): bool
    {
        return $user->can('force-delete-toll-fare');
    }
}
