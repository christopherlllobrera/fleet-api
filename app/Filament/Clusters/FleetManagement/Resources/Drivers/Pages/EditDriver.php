<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Drivers\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Drivers\DriverResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDriver extends EditRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
