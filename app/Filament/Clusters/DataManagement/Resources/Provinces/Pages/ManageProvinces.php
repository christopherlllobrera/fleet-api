<?php

namespace App\Filament\Clusters\DataManagement\Resources\Provinces\Pages;

use App\Filament\Clusters\DataManagement\Resources\Provinces\ProvinceResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageProvinces extends ManageRecords
{
    protected static string $resource = ProvinceResource::class;

    protected static ?string $title = 'Manage Provinces';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Province')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Province Created')
                        ->body('The province has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Province Created')
                        ->body('The province has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
