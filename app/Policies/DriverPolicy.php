<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-driver');
    }

    public function view(User $user, Driver $driver): bool
    {
        return $user->can('view-driver');
    }

    public function create(User $user): bool
    {
        return $user->can('create-driver');
    }

    public function update(User $user, Driver $driver): bool
    {
        return $user->can('edit-driver');
    }

    public function delete(User $user, Driver $driver): bool
    {
        return $user->can('delete-driver');
    }

    public function restore(User $user, Driver $driver): bool
    {
        return $user->can('restore-driver');
    }

    public function forceDelete(User $user, Driver $driver): bool
    {
        return $user->can('force-delete-driver');
    }
}
