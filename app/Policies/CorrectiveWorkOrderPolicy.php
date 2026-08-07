<?php

namespace App\Policies;

use App\Models\CorrectiveWorkOrder;
use App\Models\User;

class CorrectiveWorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-any-corrective-work-order');
    }

    public function view(User $user, CorrectiveWorkOrder $correctiveWorkOrder): bool
    {
        return $user->can('view-corrective-work-order');
    }

    public function create(User $user): bool
    {
        return $user->can('create-corrective-work-order');
    }

    public function update(User $user, CorrectiveWorkOrder $correctiveWorkOrder): bool
    {
        return $user->can('edit-corrective-work-order');
    }

    public function delete(User $user, CorrectiveWorkOrder $correctiveWorkOrder): bool
    {
        return $user->can('delete-corrective-work-order');
    }

    public function restore(User $user, CorrectiveWorkOrder $correctiveWorkOrder): bool
    {
        return $user->can('restore-corrective-work-order');
    }

    public function forceDelete(User $user, CorrectiveWorkOrder $correctiveWorkOrder): bool
    {
        return $user->can('force-delete-corrective-work-order');
    }
}
