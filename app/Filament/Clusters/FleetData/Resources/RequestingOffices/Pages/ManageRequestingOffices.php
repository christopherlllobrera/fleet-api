<?php

namespace App\Filament\Clusters\FleetData\Resources\RequestingOffices\Pages;

use App\Filament\Clusters\FleetData\Resources\RequestingOffices\RequestingOfficeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRequestingOffices extends ManageRecords
{
    protected static string $resource = RequestingOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
