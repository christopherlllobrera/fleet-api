<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Incidents\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Incidents\IncidentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncidents extends ListRecords
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
