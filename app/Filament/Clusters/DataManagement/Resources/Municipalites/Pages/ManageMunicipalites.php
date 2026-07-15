<?php

namespace App\Filament\Clusters\DataManagement\Resources\Municipalites\Pages;

use App\Filament\Clusters\DataManagement\Resources\Municipalites\MunicipalitesResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageMunicipalites extends ManageRecords
{
    protected static string $resource = MunicipalitesResource::class;

    protected static ?string $title = 'Manage Municipality/City';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Municipality/City')
                ->modalWidth(Width::ExtraLarge)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Municipality/City Created')
                        ->body('The municipality/city has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Municipality/City Created')
                        ->body('The municipality/city has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
