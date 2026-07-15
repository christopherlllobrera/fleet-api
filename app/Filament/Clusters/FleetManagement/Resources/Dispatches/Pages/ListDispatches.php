<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Dispatches\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Dispatches\DispatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDispatches extends ListRecords
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
