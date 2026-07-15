<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'employee_id',
    'date_of_birth',
    'gender',
    'civil_status',
    'address',
    'suffix_name',
    'place_of_birth',
    'nationality_id',
    'personal_number',
    'date_of_marriage',
    'spouse_name',
    'spouse_date_of_birth',
    'spouse_place_of_birth',
    'mother_name',
    'mother_date_of_birth',
    'father_name',
    'father_date_of_birth',
    'date_of_death',
    'date_of_separation',
])]

class EmployeeProfile extends Model
{
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'date_of_marriage' => 'date',
            'spouse_date_of_birth' => 'date',
            'mother_date_of_birth' => 'date',
            'father_date_of_birth' => 'date',
            'date_of_death' => 'date',
            'date_of_separation' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }
}
