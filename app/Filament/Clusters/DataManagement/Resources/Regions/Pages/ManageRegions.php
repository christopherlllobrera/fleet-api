<?php

namespace App\Filament\Clusters\DataManagement\Resources\Regions\Pages;

use App\Filament\Clusters\DataManagement\Resources\Regions\RegionResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageRegions extends ManageRecords
{
    protected static string $resource = RegionResource::class;

    protected static ?string $title = 'Manage Regions';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Region')
                ->modalWidth(Width::TwoExtraLarge)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Region Created')
                        ->body('The region has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Region Created')
                        ->body('The region has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
