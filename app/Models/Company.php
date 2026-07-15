<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'code', 'is_active',
])]
class Company extends Model
{
    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }
}
