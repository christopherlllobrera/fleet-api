<?php

namespace App\Filament\Clusters\HRManagement\Resources\Employees\Pages;

use App\Filament\Clusters\HRManagement\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Employee'),
        ];
    }
}
