<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-vehicle');
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->can('view-vehicle');
    }

    public function create(User $user): bool
    {
        return $user->can('create-vehicle');
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->can('edit-vehicle');
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->can('delete-vehicle');
    }

    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->can('restore-vehicle');
    }

    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->can('force-delete-vehicle');
    }
}
