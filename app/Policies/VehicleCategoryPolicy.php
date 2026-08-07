<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehicleCategory;

class VehicleCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-vehicle-category');
    }

    public function view(User $user, VehicleCategory $vehicleCategory): bool
    {
        return $user->can('view-vehicle-category');
    }

    public function create(User $user): bool
    {
        return $user->can('create-vehicle-category');
    }

    public function update(User $user, VehicleCategory $vehicleCategory): bool
    {
        return $user->can('edit-vehicle-category');
    }

    public function delete(User $user, VehicleCategory $vehicleCategory): bool
    {
        return $user->can('delete-vehicle-category');
    }

    public function restore(User $user, VehicleCategory $vehicleCategory): bool
    {
        return $user->can('restore-vehicle-category');
    }

    public function forceDelete(User $user, VehicleCategory $vehicleCategory): bool
    {
        return $user->can('force-delete-vehicle-category');
    }
}
