<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_id',
    'charge_account_id',
    'business_unit_id',
    'ownership',
    'device_sn',
    'plate_no',
    'maker_id',
    'vehicle_category_id',
    'vehicle_group_id',
    'vehicle_power_type_id',
    'model',
    'year',
    'status',
])]

class Vehicle extends Model
{
    public function business_unit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicleCategory()
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function vehiclePowerType()
    {
        return $this->belongsTo(VehiclePowerType::class);
    }

    public function vehicleGroup()
    {
        return $this->belongsTo(VehicleGroup::class);
    }

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }

    public function correctiveWorkOrders()
    {
        return $this->hasMany(CorrectiveWorkOrder::class, 'plate_no_id');
    }
}
