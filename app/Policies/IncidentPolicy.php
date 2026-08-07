<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;

class IncidentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-incident');
    }

    public function view(User $user, Incident $incident): bool
    {
        return $user->can('view-incident');
    }

    public function create(User $user): bool
    {
        return $user->can('create-incident');
    }

    public function update(User $user, Incident $incident): bool
    {
        return $user->can('edit-incident');
    }

    public function delete(User $user, Incident $incident): bool
    {
        return $user->can('delete-incident');
    }

    public function restore(User $user, Incident $incident): bool
    {
        return $user->can('restore-incident');
    }

    public function forceDelete(User $user, Incident $incident): bool
    {
        return $user->can('force-delete-incident');
    }
}
