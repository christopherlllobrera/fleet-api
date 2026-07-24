<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class CorrectiveWorkOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        // Job Order Information
        'job_order_no',
        'job_order_sap_no',
        'billing_invoice_no',
        'charge_account_no',

        // Vehicle Information
        'plate_no_id',
        'vehicle_location',
        'odometer_reading',
        'driver_name_id',

        // Office and Contact Information
        'requisition_office',
        'contact_person_id',

        // Job Details
        'vehicle_trouble_report',
        'initial_assessment',

        // Work Information
        'actual_work_time', // json
        // 'material_used', // json
        'issuance_of_materials', // json
        'return_of_materials', // json

        // Release Information
        'vehicle_date_released', // json
        'status',

        //new columns
        'type',
        'assignment',
        'UCR_ref_no',
        'UCR_amount',
        'invoice',
        'file_attachment',
    ];

    protected $casts = [
        'actual_work_time' => 'array',
        'vehicle_date_released' => 'array',
        'issuance_of_materials' => 'array',
        'return_of_materials' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Corrective Work Order')
            ->setDescriptionForEvent(fn(string $event) => "Corrective Work Order has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            // ->dontSubmitEmptyLogs()
            ;
    }

    protected $table = 'corrective_work_order';

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function driverName()
    {
        return $this->belongsTo(Employee::class, 'driver_name_id');
    }

    public function contactPerson()
    {
        return $this->belongsTo(Employee::class, 'contact_person_id');
    }

    public function getFullNameAttribute()
    {
        return $this->employee ? trim($this->employee->first_name . ' ' . $this->employee->last_name) : 'No Name';
    }

    public function plateNo()
    {
        return $this->belongsTo(Vehicle::class, 'plate_no_id');
    }

    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class, 'job_order_id', 'job_order_no');
    }
}
