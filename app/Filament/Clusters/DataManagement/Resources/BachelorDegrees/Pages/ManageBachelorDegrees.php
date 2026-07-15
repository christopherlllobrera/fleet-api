<?php

namespace App\Filament\Clusters\DataManagement\Resources\BachelorDegrees\Pages;

use App\Filament\Clusters\DataManagement\Resources\BachelorDegrees\BachelorDegreeResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageBachelorDegrees extends ManageRecords
{
    protected static string $resource = BachelorDegreeResource::class;

    protected static ?string $title = 'Manage Bachelor Degrees';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Bachelor Degree')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Bachelor Degree Created')
                        ->body('The bachelor degree has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Bachelor Degree Created')
                        ->body('The bachelor degree has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
