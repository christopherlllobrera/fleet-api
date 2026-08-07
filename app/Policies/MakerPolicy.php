<?php

namespace App\Policies;

use App\Models\Maker;
use App\Models\User;

class MakerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-maker');
    }

    public function view(User $user, Maker $maker): bool
    {
        return $user->can('view-maker');
    }

    public function create(User $user): bool
    {
        return $user->can('create-maker');
    }

    public function update(User $user, Maker $maker): bool
    {
        return $user->can('edit-maker');
    }

    public function delete(User $user, Maker $maker): bool
    {
        return $user->can('delete-maker');
    }

    public function restore(User $user, Maker $maker): bool
    {
        return $user->can('restore-maker');
    }

    public function forceDelete(User $user, Maker $maker): bool
    {
        return $user->can('force-delete-maker');
    }
}
