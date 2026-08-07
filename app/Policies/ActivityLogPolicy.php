<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-activity-log');
    }

    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('view-activity-log');
    }

    public function create(User $user): bool
    {
        return $user->can('create-activity-log');
    }

    public function update(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('edit-activity-log');
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('delete-activity-log');
    }

    public function restore(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('restore-activity-log');
    }

    public function forceDelete(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('force-delete-activity-log');
    }
}
