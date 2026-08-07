<?php

namespace App\Policies;

use App\Models\Odometer;
use App\Models\User;

class OdometerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-odometer');
    }

    public function view(User $user, Odometer $odometer): bool
    {
        return $user->can('view-odometer');
    }

    public function create(User $user): bool
    {
        return $user->can('create-odometer');
    }

    public function update(User $user, Odometer $odometer): bool
    {
        return $user->can('edit-odometer');
    }

    public function delete(User $user, Odometer $odometer): bool
    {
        return $user->can('delete-odometer');
    }

    public function restore(User $user, Odometer $odometer): bool
    {
        return $user->can('restore-odometer');
    }

    public function forceDelete(User $user, Odometer $odometer): bool
    {
        return $user->can('force-delete-odometer');
    }
}
