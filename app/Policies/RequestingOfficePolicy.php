<?php

namespace App\Policies;

use App\Models\RequestingOffice;
use App\Models\User;

class RequestingOfficePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-requesting-office');
    }

    public function view(User $user, RequestingOffice $requestingOffice): bool
    {
        return $user->can('view-requesting-office');
    }

    public function create(User $user): bool
    {
        return $user->can('create-requesting-office');
    }

    public function update(User $user, RequestingOffice $requestingOffice): bool
    {
        return $user->can('edit-requesting-office');
    }

    public function delete(User $user, RequestingOffice $requestingOffice): bool
    {
        return $user->can('delete-requesting-office');
    }

    public function restore(User $user, RequestingOffice $requestingOffice): bool
    {
        return $user->can('restore-requesting-office');
    }

    public function forceDelete(User $user, RequestingOffice $requestingOffice): bool
    {
        return $user->can('force-delete-requesting-office');
    }
}
