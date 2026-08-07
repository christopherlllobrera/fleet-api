<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-department');
    }

    public function view(User $user, Department $department): bool
    {
        return $user->can('view-department');
    }

    public function create(User $user): bool
    {
        return $user->can('create-department');
    }

    public function update(User $user, Department $department): bool
    {
        return $user->can('edit-department');
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->can('delete-department');
    }

    public function restore(User $user, Department $department): bool
    {
        return $user->can('restore-department');
    }

    public function forceDelete(User $user, Department $department): bool
    {
        return $user->can('force-delete-department');
    }
}
