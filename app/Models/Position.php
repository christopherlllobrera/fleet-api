<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['position_no', 'position_description', 'department_id'])]

class Position extends Model
{
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
