<?php

namespace App\Policies;

use App\Models\TollRoad;
use App\Models\User;

class TollRoadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-toll-road');
    }

    public function view(User $user, TollRoad $tollRoad): bool
    {
        return $user->can('view-toll-road');
    }

    public function create(User $user): bool
    {
        return $user->can('create-toll-road');
    }

    public function update(User $user, TollRoad $tollRoad): bool
    {
        return $user->can('edit-toll-road');
    }

    public function delete(User $user, TollRoad $tollRoad): bool
    {
        return $user->can('delete-toll-road');
    }

    public function restore(User $user, TollRoad $tollRoad): bool
    {
        return $user->can('restore-toll-road');
    }

    public function forceDelete(User $user, TollRoad $tollRoad): bool
    {
        return $user->can('force-delete-toll-road');
    }
}
