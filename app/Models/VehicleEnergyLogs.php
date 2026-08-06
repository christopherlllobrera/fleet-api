<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['dispatch_id', 'vehicle_id', 'reference_no', 'power_type_id', 'date', 'cost', 'attachment'])]

class VehicleEnergyLogs extends Model
{
    protected $casts = [
        'cost' => 'decimal:2',
        'attachment' => 'array',
    ];

    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function powerType(): BelongsTo
    {
        return $this->belongsTo(VehiclePowerType::class, 'power_type_id');
    }
}
