<?php

namespace App\Filament\Clusters\Settings\Users\Pages;

use App\Filament\Clusters\Settings\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
