<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['employee_id', 'tin_no', 'sss_no', 'pag_ibig_no', 'philhealth_no'])]

class EmployeeGovernmentInfo extends Model
{
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
