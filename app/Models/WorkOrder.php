<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class WorkOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'job_order_id',
        'company_id',
        'contracted_attachment',
        'start_date',
        'end_date',
        'contract_amount',
        'contact_person_name',
        'contact_person_email',
        'contact_person_no',
    ];

    protected $cast = [
        'contracted_attachment' => 'array',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Work Order')
            ->setDescriptionForEvent(fn (string $event) => "Work order has been {$event}")
            ->logAll()
            ->logOnlyDirty();
    }

    public function preventive_work_order()
    {
        return $this->HasMany(PreventiveWorkOrder::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFullNameAttribute()
    {
        return $this->employee ? trim($this->employee->first_name.' '.$this->employee->last_name) : 'No Name';
    }
}
