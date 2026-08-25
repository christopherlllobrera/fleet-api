<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PreventiveWorkOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'vehicle_id',
        'job_order_no',
        'job_order_date',

        // new columns
        'type',
        'assignment',
        'UCR_ref_no',
        'UCR_amount',
        'invoice',
        'file_attachment',

        'preventive_maintenance_type',
        'job_order_assigned_date',
        'job_order_accomplished_date',
        'supervisor_id',
        'leadman_id',
        'engine_item', // json
        'steering_item', // json
        'brake_item', // json
        'exhaust_item', // json
        'front_suspension_item', // json
        'rear_axle_item', // json
        'clutch_item', // json
        'transmission_item', // json
        'propeller_item', // json
        'tire_item', // json
        'electrical_item', // json
        'body_item', // json
        'pms_tag_format', // boolean
        'pms_next_schedule',
        'odometer_reading',
        'plate_number_id',
        'driver_id',
        'date_of_pms',
        'pms_tagging',
    ];

    protected $casts = [
        'engine_item' => 'array',
        'steering_item' => 'array',
        'brake_item' => 'array',
        'exhaust_item' => 'array',
        'front_suspension_item' => 'array',
        'rear_axle_item' => 'array',
        'clutch_item' => 'array',
        'transmission_item' => 'array',
        'propeller_item' => 'array',
        'tire_item' => 'array',
        'electrical_item' => 'array',
        'body_item' => 'array',
        'pms_tag_format' => 'boolean',
        'pms_next_schedule' => 'boolean',
        'odometer_reading' => 'boolean',
        'plate_number_id' => 'boolean',
        'driver_id' => 'boolean',
        'date_of_pms' => 'boolean',
        'pms_tagging' => 'array',
        'file_attachment' => 'array',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Preventive Work Order')
            ->setDescriptionForEvent(fn (string $event) => "Preventive Work Order has been {$event}")
            ->logAll()
            ->logOnlyDirty();
        // ->dontSubmitEmptyLogs()
    }

    public function plateNo()
    {
        return $this->belongsTo(Vehicle::class, 'plate_no_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFullNameAttribute()
    {
        return $this->employee ? trim($this->employee->first_name.' '.$this->employee->last_name) : 'No Name';
    }

    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class, 'job_order_id', 'job_order_no');
    }
}
