<?php

namespace App\Filament\Clusters\HRManagement\Resources\Positions\Pages;

use App\Filament\Clusters\HRManagement\Resources\Positions\PositionResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;

class ManagePositions extends ManageRecords
{
    protected static string $resource = PositionResource::class;

    protected static ?string $title = 'Manage Positions';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create')
                ->modalHeading('Add Position')
                ->modalWidth(Width::ExtraLarge)
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Position Created')
                        ->body('The position has been successfully created.'),
                )
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Position Created')
                        ->body('The position has been successfully created.')
                        ->sendToDatabase($recipient = Auth::user());
                }),
        ];
    }
}
