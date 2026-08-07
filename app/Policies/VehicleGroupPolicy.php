<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehicleGroup;

class VehicleGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-vehicle-group');
    }

    public function view(User $user, VehicleGroup $vehicleGroup): bool
    {
        return $user->can('view-vehicle-group');
    }

    public function create(User $user): bool
    {
        return $user->can('create-vehicle-group');
    }

    public function update(User $user, VehicleGroup $vehicleGroup): bool
    {
        return $user->can('edit-vehicle-group');
    }

    public function delete(User $user, VehicleGroup $vehicleGroup): bool
    {
        return $user->can('delete-vehicle-group');
    }

    public function restore(User $user, VehicleGroup $vehicleGroup): bool
    {
        return $user->can('restore-vehicle-group');
    }

    public function forceDelete(User $user, VehicleGroup $vehicleGroup): bool
    {
        return $user->can('force-delete-vehicle-group');
    }
}
