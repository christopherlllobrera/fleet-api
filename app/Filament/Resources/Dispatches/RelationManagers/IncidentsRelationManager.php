<?php

namespace App\Filament\Resources\Dispatches\RelationManagers;

use App\Filament\Resources\Incidents\IncidentsResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncidentsRelationManager extends RelationManager
{
    protected static string $relationship = 'incidents';

    protected static ?string $relatedResource = IncidentsResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference_no')
            ->columns([
                TextColumn::make('reference_no')
                    ->label('Reference No')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('incident_severity')
                    ->label('Severity')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Low' => 'success',
                        'Minor' => 'info',
                        'Major' => 'warning',
                        'Critical' => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Urgent' => 'danger',
                        'High' => 'warning',
                        'Normal' => 'info',
                        'Low' => 'success',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'danger',
                        'In Progress' => 'warning',
                        'Resolved' => 'success',
                        'Closed' => 'gray',
                        'Cancelled' => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('reported_at')
                    ->label('Reported Date')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('reported_at', 'desc')
            ->headerActions([
                CreateAction::make()->label('Report Incident'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
