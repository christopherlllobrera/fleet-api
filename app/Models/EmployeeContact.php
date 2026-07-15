<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['employee_id', 'type', 'value'])]

class EmployeeContact extends Model
{
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
