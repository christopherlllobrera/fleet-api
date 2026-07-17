<?php

namespace App\Filament\Resources\Incidents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')
                    ->label('Company')->searchable(),
                TextColumn::make('reference_no')
                    ->label('Reference No.')->searchable(),
                TextColumn::make('dispatch.ticket_no')
                    ->label('Ticket No.')->sortable(),
                TextColumn::make('vehicle.plate_no')
                    ->label('Plate No.')->sortable(),
                TextColumn::make('reported_at')
                    ->label('Reported At')
                    ->searchable()
                    ->date(),
                TextColumn::make('incident_severity')
                    ->label('Severity')->searchable(),
                TextColumn::make('type')
                    ->label('Type')->searchable(),
                TextColumn::make('priority')
                    ->label('Priority')->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'Open' => 'Open',
                        'In Progress' => 'In Progress',
                        'Resolved' => 'Resolved',
                        'Closed' => 'Closed',
                        'Cancelled' => 'Cancelled',
                    ]),
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
