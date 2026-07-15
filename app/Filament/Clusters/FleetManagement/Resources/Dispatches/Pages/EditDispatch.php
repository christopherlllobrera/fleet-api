<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Dispatches\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Dispatches\DispatchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDispatch extends EditRecord
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
