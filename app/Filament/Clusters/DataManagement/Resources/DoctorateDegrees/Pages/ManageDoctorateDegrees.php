<?php

namespace App\Filament\Clusters\DataManagement\Resources\DoctorateDegrees\Pages;

use App\Filament\Clusters\DataManagement\Resources\DoctorateDegrees\DoctorateDegreeResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManageDoctorateDegrees extends ManageRecords
{
    protected static string $resource = DoctorateDegreeResource::class;

    protected static ?string $title = 'Manage Doctorate Degrees';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Doctorate Degree')
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Doctorate Degree Created')
                        ->body('The doctorate degree has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Doctorate Degree Created')
                        ->body('The doctorate degree has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
