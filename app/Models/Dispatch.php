<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'ticket_no', 'vehicle_id',
    'driver_id', 'requesting_office_id',
    'from_location', 'to_location',
    'purpose', 'priority_level',
    'departure_time', 'en_route_time',
    'complete_time', 'cancel_time',
    'reason', 'status',
])]

class Dispatch extends Model
{
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function requesting_office()
    {
        return $this->belongsTo(RequestingOffice::class);
    }
}
