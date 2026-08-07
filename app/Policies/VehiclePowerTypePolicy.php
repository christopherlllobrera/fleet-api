<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehiclePowerType;

class VehiclePowerTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-vehicle-power-type');
    }

    public function view(User $user, VehiclePowerType $vehiclePowerType): bool
    {
        return $user->can('view-vehicle-power-type');
    }

    public function create(User $user): bool
    {
        return $user->can('create-vehicle-power-type');
    }

    public function update(User $user, VehiclePowerType $vehiclePowerType): bool
    {
        return $user->can('edit-vehicle-power-type');
    }

    public function delete(User $user, VehiclePowerType $vehiclePowerType): bool
    {
        return $user->can('delete-vehicle-power-type');
    }

    public function restore(User $user, VehiclePowerType $vehiclePowerType): bool
    {
        return $user->can('restore-vehicle-power-type');
    }

    public function forceDelete(User $user, VehiclePowerType $vehiclePowerType): bool
    {
        return $user->can('force-delete-vehicle-power-type');
    }
}
