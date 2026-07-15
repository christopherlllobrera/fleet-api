<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Vehicles\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Vehicles\VehicleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicle extends EditRecord
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
