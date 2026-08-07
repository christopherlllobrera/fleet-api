<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-company');
    }

    public function view(User $user, Company $company): bool
    {
        return $user->can('view-company');
    }

    public function create(User $user): bool
    {
        return $user->can('create-company');
    }

    public function update(User $user, Company $company): bool
    {
        return $user->can('edit-company');
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->can('delete-company');
    }

    public function restore(User $user, Company $company): bool
    {
        return $user->can('restore-company');
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return $user->can('force-delete-company');
    }
}
