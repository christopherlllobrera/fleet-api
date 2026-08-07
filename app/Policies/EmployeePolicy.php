<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-employee');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->can('view-employee');
    }

    public function create(User $user): bool
    {
        return $user->can('create-employee');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->can('edit-employee');
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->can('delete-employee');
    }

    public function restore(User $user, Employee $employee): bool
    {
        return $user->can('restore-employee');
    }

    public function forceDelete(User $user, Employee $employee): bool
    {
        return $user->can('force-delete-employee');
    }
}
