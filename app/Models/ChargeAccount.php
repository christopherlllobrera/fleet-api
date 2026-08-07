<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'business_unit_id',
])]

class ChargeAccount extends Model
{
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
