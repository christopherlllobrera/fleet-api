<?php

namespace App\Filament\Clusters\HRManagement\Resources\BusinessUnits\Pages;

use App\Filament\Clusters\HRManagement\Resources\BusinessUnits\BusinessUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBusinessUnits extends ManageRecords
{
    protected static string $resource = BusinessUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
