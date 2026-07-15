<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['region_id', 'province_name'])]

class Province extends Model
{
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
