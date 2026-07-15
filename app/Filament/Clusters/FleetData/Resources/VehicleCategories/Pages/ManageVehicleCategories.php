<?php

namespace App\Filament\Clusters\FleetData\Resources\VehicleCategories\Pages;

use App\Filament\Clusters\FleetData\Resources\VehicleCategories\VehicleCategoriesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVehicleCategories extends ManageRecords
{
    protected static string $resource = VehicleCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
