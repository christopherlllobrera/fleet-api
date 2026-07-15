<?php

namespace App\Filament\Clusters\DataManagement\Resources\MasteralDegrees\Pages;

use App\Filament\Clusters\DataManagement\Resources\MasteralDegrees\MasteralDegreeResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageMasteralDegrees extends ManageRecords
{
    protected static string $resource = MasteralDegreeResource::class;

    protected static ?string $title = 'Manage Masteral Degrees';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Masteral Degree')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Masteral Degree Created')
                        ->body('The masteral degree has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Masteral Degree Created')
                        ->body('The masteral degree has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
