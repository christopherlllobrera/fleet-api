<?php

namespace App\Filament\Resources\Dispatches\Pages;

use App\Filament\Resources\Dispatches\DispatchResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

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

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('New Dispatch')
            ->success()
            ->body('A new dispatch has been created')
            ->send();
    }
}
