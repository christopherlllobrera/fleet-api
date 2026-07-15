<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Vehicles\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Vehicles\VehicleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
