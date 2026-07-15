<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Odometers\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Odometers\OdometerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOdometers extends ListRecords
{
    protected static string $resource = OdometerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
