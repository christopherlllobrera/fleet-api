<?php

namespace App\Filament\Clusters\DataManagement\Resources\Schools\Pages;

use App\Filament\Clusters\DataManagement\Resources\Schools\SchoolResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageSchools extends ManageRecords
{
    protected static string $resource = SchoolResource::class;

    protected static ?string $title = 'Manage Schools';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add School')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('School Created')
                        ->body('The school has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('School Created')
                        ->body('The school has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
