<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Odometers\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Odometers\OdometerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOdometer extends EditRecord
{
    protected static string $resource = OdometerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
