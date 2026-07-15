<?php

namespace App\Filament\Clusters\HRManagement\Resources\Employees\Pages;

use App\Filament\Clusters\HRManagement\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
