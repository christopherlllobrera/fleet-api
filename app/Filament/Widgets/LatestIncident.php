<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Incidents\IncidentsResource;
use App\Models\Incident;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class LatestIncident extends TableWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $title = 'Latest Incident';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                $startDate = filled($this->pageFilters['startDate'] ?? null)
                    ? Carbon::parse($this->pageFilters['startDate'])
                    : null;
                $endDate = filled($this->pageFilters['endDate'] ?? null)
                    ? Carbon::parse($this->pageFilters['endDate'])
                    : now();

                return Incident::query()
                    ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
                    ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate));
            })
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
