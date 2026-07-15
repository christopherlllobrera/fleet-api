<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Dispatches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DispatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_no')
                    ->searchable(),
                TextColumn::make('vehicle_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('driver_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requesting_office_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('from_location')
                    ->searchable(),
                TextColumn::make('to_location')
                    ->searchable(),
                TextColumn::make('purpose')
                    ->searchable(),
                TextColumn::make('priority_level')
                    ->searchable(),
                TextColumn::make('departure_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('en_route_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('complete_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cancel_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reason')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
