<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['name', 'operator', 'region', 'is_active'])]
class TollRoad extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Toll Road')
            ->setDescriptionForEvent(fn (string $event) => "Toll road has been {$event}")
            ->logAll()
            ->logOnlyDirty();
    }

    public function tollPoints(): HasMany
    {
        return $this->hasMany(TollPoint::class);
    }

    public function tollFares(): HasMany
    {
        return $this->hasMany(TollFare::class);
    }
}
