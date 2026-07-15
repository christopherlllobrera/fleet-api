<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['country_name', 'phone_directory'])]

class Country extends Model
{
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }
}
