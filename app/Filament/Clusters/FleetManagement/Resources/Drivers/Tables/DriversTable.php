<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Drivers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.employee_no')
                    ->label('Employee No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('license_no')
                    ->label('License No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('license_class')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('medical_expiry')
                    ->date()
                    ->sortable(),
                TextColumn::make('country.country_name')
                    ->label('Issuing Country')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
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
