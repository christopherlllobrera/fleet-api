<?php

namespace App\Policies;

use App\Models\TollPoint;
use App\Models\User;

class TollPointPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-toll-point');
    }

    public function view(User $user, TollPoint $tollPoint): bool
    {
        return $user->can('view-toll-point');
    }

    public function create(User $user): bool
    {
        return $user->can('create-toll-point');
    }

    public function update(User $user, TollPoint $tollPoint): bool
    {
        return $user->can('edit-toll-point');
    }

    public function delete(User $user, TollPoint $tollPoint): bool
    {
        return $user->can('delete-toll-point');
    }

    public function restore(User $user, TollPoint $tollPoint): bool
    {
        return $user->can('restore-toll-point');
    }

    public function forceDelete(User $user, TollPoint $tollPoint): bool
    {
        return $user->can('force-delete-toll-point');
    }
}
