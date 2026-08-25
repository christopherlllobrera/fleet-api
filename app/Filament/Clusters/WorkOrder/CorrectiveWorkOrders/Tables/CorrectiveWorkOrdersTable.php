<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Tables;

use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Actions\MaterialUseAction;
use App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Actions\WorkTimeTrackingAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CorrectiveWorkOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_order_no')
                    ->label('Job Order No.')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->searchable(),
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
                ActionGroup::make([
                    EditAction::make(),
                    WorkTimeTrackingAction::make(),
                    MaterialUseAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
