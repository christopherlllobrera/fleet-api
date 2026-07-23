<?php

namespace App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PreventiveWorkOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_order_no')
                    ->label('Job Order No.')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vehicle.plate_no')
                    ->label('Plate Number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('job_order_date')
                    ->label('Job Order Date')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('preventive_maintenance_type')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('job_order_assigned_date')
                    ->label('Assigned Date')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('job_order_accomplished_date')
                    ->label('Accomplished Date')
                    ->sortable()
                    ->searchable()
                    ->date(),
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
