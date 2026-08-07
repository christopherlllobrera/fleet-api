<?php

namespace App\Policies;

use App\Models\BusinessUnit;
use App\Models\User;

class BusinessUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-business-unit');
    }

    public function view(User $user, BusinessUnit $businessUnit): bool
    {
        return $user->can('view-business-unit');
    }

    public function create(User $user): bool
    {
        return $user->can('create-business-unit');
    }

    public function update(User $user, BusinessUnit $businessUnit): bool
    {
        return $user->can('edit-business-unit');
    }

    public function delete(User $user, BusinessUnit $businessUnit): bool
    {
        return $user->can('delete-business-unit');
    }

    public function restore(User $user, BusinessUnit $businessUnit): bool
    {
        return $user->can('restore-business-unit');
    }

    public function forceDelete(User $user, BusinessUnit $businessUnit): bool
    {
        return $user->can('force-delete-business-unit');
    }
}
