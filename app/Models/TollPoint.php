<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['toll_road_id', 'name', 'type', 'latitude',
    'longitude', 'payment_method', 'is_active'])]
class TollPoint extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'payment_method' => 'array',
        'is_active' => 'boolean',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Toll Point')
            ->setDescriptionForEvent(fn (string $event) => "Toll point has been {$event}")
            ->logAll()
            ->logOnlyDirty();
    }

    public function tollRoad(): BelongsTo
    {
        return $this->belongsTo(TollRoad::class);
    }

    public function entrySideOfFares(): HasMany
    {
        return $this->hasMany(TollFare::class, 'entry_point_id');
    }

    public function exitSideOfFares(): HasMany
    {
        return $this->hasMany(TollFare::class, 'exit_point_id');
    }
}
