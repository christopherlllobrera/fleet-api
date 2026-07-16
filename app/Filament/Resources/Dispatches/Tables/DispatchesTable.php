<?php

namespace App\Filament\Resources\Dispatches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;

class DispatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_no')
                    ->searchable()
                    ->label('Ticket No.'),
                SelectColumn::make('status')
                    ->options([
                    'Pending' => 'Pending',
                    'Assigned' => 'Assigned',
                    'Unassigned' => 'Unassigned',
                    'Unserved' => 'Unserved',
                    'Cancelled' => 'Cancelled',
                    'Completed' => 'Completed',
                    ])
                    ->searchable(),
                TextColumn::make('vehicle.plate_no')
                    ->label('Plate No.')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('driver.employee.full_name')
                    ->label('Driver')
                    ->sortable(),
                TextColumn::make('requesting_office.office_name')
                    ->label('Office')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(25),
                TextColumn::make('from_location')
                    ->label('From')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-map-pin')
                    ->limit(30),
                TextColumn::make('to_location')
                    ->label('To')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-map-pin')
                    ->limit(30),    
                TextColumn::make('priority_level')
                    ->label('Priority Level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                    })
                    ->searchable(),
                TextColumn::make('departure_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->emptyStateHeading('No dispatch yet')
            ->emptyStateDescription('Once you create your first dispatch, it will appear here.')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
