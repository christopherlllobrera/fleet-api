<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Incidents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_no')
                    ->searchable(),
                TextColumn::make('reference_no')
                    ->searchable(),
                TextColumn::make('dispatch_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vehicle_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reported_by')
                    ->searchable(),
                TextColumn::make('reported_at')
                    ->searchable(),
                TextColumn::make('incident_location')
                    ->searchable(),
                TextColumn::make('incident_description')
                    ->searchable(),
                TextColumn::make('incident_severity')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('priority')
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
