<?php

namespace App\Filament\Clusters\FleetData\Resources\VehicleGroups\Pages;

use App\Filament\Clusters\FleetData\Resources\VehicleGroups\VehicleGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVehicleGroups extends ManageRecords
{
    protected static string $resource = VehicleGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
