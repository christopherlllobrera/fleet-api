<?php

namespace App\Filament\Clusters\FleetData\Resources\VehiclePowerTypes\Pages;

use App\Filament\Clusters\FleetData\Resources\VehiclePowerTypes\VehiclePowerTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVehiclePowerTypes extends ManageRecords
{
    protected static string $resource = VehiclePowerTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
