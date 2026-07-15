<?php

namespace App\Filament\Clusters\DataManagement\Resources\Barangays\Pages;

use App\Filament\Clusters\DataManagement\Resources\Barangays\BarangayResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageBarangays extends ManageRecords
{
    protected static string $resource = BarangayResource::class;

    protected static ?string $title = 'Manage Barangay';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Barangay')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Barangay Created')
                        ->body('The barangay has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Barangay Created')
                        ->body('The barangay has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
