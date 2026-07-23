<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Incident;
use App\Filament\Resources\Incidents\IncidentsResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Actions\Action;

class LatestIncident extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $title = 'Latest Incident';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Incident::query())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('open')
                    ->url(fn (Incident $record): string => IncidentsResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
