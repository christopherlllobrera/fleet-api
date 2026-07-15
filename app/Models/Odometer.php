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
    //
}
