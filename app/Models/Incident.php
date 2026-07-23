<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_id', 'reference_no',
    'dispatch_id', 'incident_severity', 'type',
    'vehicle_id', 'reported_by', 'reported_at',
    'location', 'priority', 'status',
    'description', 'attachments'])]

class Incident extends Model
{
    protected $casts = [
        'attachments' => 'array',

    ];

    // relationship

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
