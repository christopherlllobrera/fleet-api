<?php

namespace App\Policies;

use App\Models\Dispatch;
use App\Models\User;

class DispatchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-dispatch');
    }

    public function view(User $user, Dispatch $dispatch): bool
    {
        return $user->can('view-dispatch');
    }

    public function create(User $user): bool
    {
        return $user->can('create-dispatch');
    }

    public function update(User $user, Dispatch $dispatch): bool
    {
        return $user->can('edit-dispatch');
    }

    public function delete(User $user, Dispatch $dispatch): bool
    {
        return $user->can('delete-dispatch');
    }

    public function restore(User $user, Dispatch $dispatch): bool
    {
        return $user->can('restore-dispatch');
    }

    public function forceDelete(User $user, Dispatch $dispatch): bool
    {
        return $user->can('force-delete-dispatch');
    }
}
