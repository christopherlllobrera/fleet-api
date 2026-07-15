<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['municipality_id', 'barangay_name'])]

class Barangay extends Model
{
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
