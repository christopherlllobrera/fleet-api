<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Incidents\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;
}
