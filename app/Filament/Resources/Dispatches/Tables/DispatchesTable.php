<?php

namespace App\Filament\Resources\Dispatches\Tables;

use App\Filament\Resources\Dispatches\Actions\FuelAction;
use App\Filament\Resources\Dispatches\Actions\IncidentAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use App\Filament\Resources\Dispatches\Pages\ViewDispatch;
use App\Models\Dispatch;

class DispatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_no')
                    ->searchable()
                    ->label('Ticket No.'),
                SelectColumn::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Assigned' => 'Assigned',
                        'En Route' => 'En Route',
                        'Unassigned' => 'Unassigned',
                        'Unserved' => 'Unserved',
                        'Cancelled' => 'Cancelled',
                        'Completed' => 'Completed',
                    ])
                    ->searchable(),
                TextColumn::make('vehicle.plate_no')
                    ->label('Plate No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('driver.employee.full_name')
                    ->label('Driver')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('driver.employee', function (Builder $employeeQuery) use ($search): void {
                            $employeeQuery
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                    })
                    ->sortable(),
                TextColumn::make('requesting_office.office_name')
                    ->label('Office')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(25),
                TextColumn::make('from_location')
                    ->label('From')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-map-pin')
                    ->limit(30),
                TextColumn::make('to_location')
                    ->label('To')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-map-pin')
                    ->limit(30),
                TextColumn::make('priority_level')
                    ->label('Priority Level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                    })
                    ->searchable(),
                TextColumn::make('departure_time')
                    ->time()
                    ->sortable()
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
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->emptyStateHeading('No dispatch yet')
            ->emptyStateDescription('Once you create your first dispatch, it will appear here.')
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    FuelAction::make(),
                    IncidentAction::make(),
                    Action::make('view_dispatch')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Dispatch $record): string => ViewDispatch::getUrl(['record' => $record])), 

                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
