<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['employee_id', 'type', 'file_path', 'description'])]

class EmployeeAttachment extends Model
{
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
