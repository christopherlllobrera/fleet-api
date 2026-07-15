<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'employee_id',
    'degree_type',
    'degree_name',
    'school_id',
    'start_date',
    'end_date',
    'duration_of_course',
    'final_grade',
])]

class EmployeeEducation extends Model
{
    /**
     * Explicit table name to avoid inflection issues.
     */
    protected $table = 'employee_educations';
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'date_of_birth' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
