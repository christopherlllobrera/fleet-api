<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['company_id', 'name'])]

class BusinessUnit extends Model
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
