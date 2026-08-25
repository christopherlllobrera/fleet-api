<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'ticket_no', 'request_item', 'passenger_count', 'vehicle_id',
    'driver_id', 'requesting_office_id',
    'from_location', 'from_lat', 'from_lng',
    'to_location', 'to_lat', 'to_lng',
    'purpose', 'priority_level',
    'departure_time', 'en_route_time',
    'complete_time', 'cancel_time',
    'reason', 'status',
])]

class Dispatch extends Model
{
    protected function casts(): array
    {
        return [
            'from_location' => 'array',
            'to_location' => 'array',
        ];
    }

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

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function vehicleEnergyLogs()
    {
        return $this->hasMany(VehicleEnergyLogs::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function tolls()
    {
        return $this->hasMany(Toll::class);
    }
}
