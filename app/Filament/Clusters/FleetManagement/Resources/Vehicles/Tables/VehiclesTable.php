<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name'),
                TextColumn::make('business_unit.name'),
                TextColumn::make('plate_no'),
                TextColumn::make('maker.name'),
                TextColumn::make('model'),
                TextColumn::make('year'),
                TextColumn::make('status'),
                TextColumn::make('vehicleCategory.name'),
                TextColumn::make('vehiclePowerType.name'),
                TextColumn::make('vehicleGroup.name'),
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
