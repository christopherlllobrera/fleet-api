<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['province_id', 'municipality_id', 'municipality_name'])]

class Municipality extends Model
{
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
