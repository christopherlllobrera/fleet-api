<?php

namespace App\Filament\Clusters\FleetData\Resources\Makers\Pages;

use App\Filament\Clusters\FleetData\Resources\Makers\MakerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMakers extends ManageRecords
{
    protected static string $resource = MakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
