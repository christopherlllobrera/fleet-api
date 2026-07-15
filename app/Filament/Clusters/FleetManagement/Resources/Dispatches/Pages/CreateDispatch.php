<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Dispatches\Pages;

use App\Filament\Clusters\FleetManagement\Resources\Dispatches\DispatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDispatch extends CreateRecord
{
    protected static string $resource = DispatchResource::class;

    protected function getFormActions(): array
    {
        return [
            // $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}
