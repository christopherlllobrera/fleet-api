<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'employee_id',
    'institution',
    'license',
    'license_number',
    'date_issued',
    'date_expiry',
])]

class EmployeeCertification extends Model
{
    protected function casts(): array
    {
        return [
            'date_issued' => 'date',
            'date_expiry' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
