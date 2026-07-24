<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class CorrectiveWorkOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_order_no')
                    ->label('Job Order No.')
                    ->sortable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plateNo.plate_no')
                    ->label('Vehicle')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('driverName.full_name')
                    ->label('Driver')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('contactPerson.full_name')
                    ->label('Contact Person')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),


                TextColumn::make('vehicle_location')
                    ->label('Vehicle Location')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('odometer_reading')
                    ->label('Odometer')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('requisition_office')
                    ->label('Requisition Office')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
