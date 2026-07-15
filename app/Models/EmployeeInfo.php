<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'employee_id',
    'data_privacy_consent',
    'remarks',
    'status',
])]

class EmployeeInfo extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
