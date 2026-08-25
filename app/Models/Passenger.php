<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'dispatch_id',
    'name',
    'contact_no',
    'pick_up_location',
    'pick_up_lat',
    'pick_up_lng',
])]

class Passenger extends Model
{
    protected function casts(): array
    {
        return [
            'pick_up_location' => 'array',
        ];
    }

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }
}
