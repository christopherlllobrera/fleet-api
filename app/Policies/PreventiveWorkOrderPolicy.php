<?php

namespace App\Policies;

use App\Models\PreventiveWorkOrder;
use App\Models\User;

class PreventiveWorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-preventive-work-order');
    }

    public function view(User $user, PreventiveWorkOrder $preventiveWorkOrder): bool
    {
        return $user->can('view-preventive-work-order');
    }

    public function create(User $user): bool
    {
        return $user->can('create-preventive-work-order');
    }

    public function update(User $user, PreventiveWorkOrder $preventiveWorkOrder): bool
    {
        return $user->can('edit-preventive-work-order');
    }

    public function delete(User $user, PreventiveWorkOrder $preventiveWorkOrder): bool
    {
        return $user->can('delete-preventive-work-order');
    }

    public function restore(User $user, PreventiveWorkOrder $preventiveWorkOrder): bool
    {
        return $user->can('restore-preventive-work-order');
    }

    public function forceDelete(User $user, PreventiveWorkOrder $preventiveWorkOrder): bool
    {
        return $user->can('force-delete-preventive-work-order');
    }
}
