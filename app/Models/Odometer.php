<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'dispatch_id',
    'vehicle_id',
    'odometer_in',
    'odometer_out',
])]
class Odometer extends Model
{
    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
