<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'employee_id'
    'dispatch_id', 
    'name', 
    'contact_no', 
    'pick_up_location',
])]

class Passenger extends Model
{
    //
}
