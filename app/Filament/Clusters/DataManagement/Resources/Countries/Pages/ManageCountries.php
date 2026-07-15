<?php

namespace App\Filament\Clusters\DataManagement\Resources\Countries\Pages;

use App\Filament\Clusters\DataManagement\Resources\Countries\CountryResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageCountries extends ManageRecords
{
    protected static string $resource = CountryResource::class;

    protected static ?string $title = 'Manage Countries';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Country')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Country Created')
                        ->body('The country has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Country Created')
                        ->body('The country has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
