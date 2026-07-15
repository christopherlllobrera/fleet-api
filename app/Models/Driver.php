<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;


#[Fillable(['employee_id', 'license_no', 'license_expiry', 'status', 'license_class', 'medical_expiry', 'country_id',
])]
class Driver extends Model
{
    use HasFactory;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Driver')
            ->setDescriptionForEvent(fn (string $event) => "Driver has been {$event}")
            ->logAll()
            ->logOnlyDirty();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
