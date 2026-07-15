<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Incidents\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Incidents\IncidentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIncident extends EditRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
