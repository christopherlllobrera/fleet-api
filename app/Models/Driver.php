<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['employee_id', 'license_no', 'license_expiry', 'status', 'license_class', 'medical_expiry', 'country_id', 'is_active',
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

    public function getFullNameAttribute()
    {
        return $this->employee ? trim($this->employee->first_name.' '.$this->employee->last_name) : 'No Name';
    }

    public function getPersonnelNoAttribute()
    {
        return $this->employee ? $this->employee->employee_no : 'N/A';
    }

    public function getContactNoAttribute()
    {
        if ($this->employee && $this->employee->contacts->isNotEmpty()) {
            return $this->employee->contacts->first()->value;
        }

        return 'N/A';
    }
}
