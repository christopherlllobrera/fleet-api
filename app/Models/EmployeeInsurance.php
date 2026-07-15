<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['employee_id', 'provider', 'med_card_no', 'med_card_policy_no', 'valid_until'])]

class EmployeeInsurance extends Model
{
    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
