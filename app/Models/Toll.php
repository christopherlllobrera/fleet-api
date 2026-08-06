<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['dispatch_id', 'toll_road_id', 'vehicle_class', 'entry_point_id', 'exit_point_id', 'payment_method', 'toll_fare', 'toll_attachments',
])]

class Toll extends Model
{
    protected $casts = [
        'toll_fare' => 'decimal:2',
        'vehicle_class' => 'integer',
        'toll_attachments' => 'array',
    ];

    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(Dispatch::class, 'dispatch_id');
    }

    public function tollRoad(): BelongsTo
    {
        return $this->belongsTo(TollRoad::class, 'toll_road_id');
    }

    public function entryPoint(): BelongsTo
    {
        return $this->belongsTo(TollPoint::class, 'entry_point_id');
    }

    public function exitPoint(): BelongsTo
    {
        return $this->belongsTo(TollPoint::class, 'exit_point_id');
    }
}
