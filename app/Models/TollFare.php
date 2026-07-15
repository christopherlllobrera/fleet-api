<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['toll_road_id', 'entry_point_id', 'exit_point_id', 'class', 'discount', 'is_active', 'fare'])]

class TollFare extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $casts = [
        'discount' => 'float',
        'is_active' => 'boolean',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Toll Fare')
            ->setDescriptionForEvent(fn (string $event) => "Toll fare has been {$event}")
            ->logAll()
            ->logOnlyDirty();
    }

    public function tollRoad(): BelongsTo
    {
        return $this->belongsTo(TollRoad::class);
    }

    public function entryPoint(): BelongsTo
    {
        return $this->belongsTo(TollPoint::class, 'entry_point_id');
    }

    public function exitPoint(): BelongsTo
    {
        return $this->belongsTo(TollPoint::class, 'exit_point_id');
    }

    /**
     * Get the fare amount for the specified vehicle class
     *
     * @param  int  $vehicleClass  1, 2, or 3
     * @param  bool  $useRFID  Whether to apply RFID discount
     * @return float
     */
    // public function getFare(int $vehicleClass = 1, bool $useRFID = false): float
    // {
    //     $fareColumn = "fare_class_{$vehicleClass}";
    //     $fare = $this->$fareColumn;

    //     if ($useRFID && $this->rfid_discount_percent > 0) {
    //         $discount = $fare * ($this->rfid_discount_percent / 100);
    //         $fare -= $discount;
    //     }

    //     return $fare;
    // }
}
