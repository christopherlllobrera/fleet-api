<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['dispatch_id', 'vehicle_id', 'reference_no', 'energy_type_id', 'date', 'cost', 'attachment'])]

class VehicleEnergyLogs extends Model
{
    protected $casts = [
        'cost' => 'decimal:2',
        'attachment' => 'array',
    ];
}
