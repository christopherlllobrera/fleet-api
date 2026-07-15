<?php

namespace App\Filament\Clusters\DataManagement\Resources\Nationalities\Pages;

use App\Filament\Clusters\DataManagement\Resources\Nationalities\NationalityResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageNationalities extends ManageRecords
{
    protected static string $resource = NationalityResource::class;

    protected static ?string $title = 'Manage Nationality';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Nationality')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Nationality Created')
                        ->body('The nationality has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Nationality Created')
                        ->body('The nationality has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
