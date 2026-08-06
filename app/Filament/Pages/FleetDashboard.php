<?php

namespace App\Filament\Pages;

use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Dashboard;
use App\Filament\Widgets\TripOverview;
use App\Filament\Widgets\DispatchChart;
use App\Filament\Widgets\VehicleOverview;
use App\Filament\Widgets\PassengerChart;
use App\Filament\Widgets\LatestIncident;

class FleetDashboard extends Dashboard
{
    use HasFiltersForm;
    
    protected static string $routePath = 'dashboard';
    
    protected static ?string $title = 'Dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                        DatePicker::make('endDate')
                            ->minDate(fn (Get $get) => $get('startDate') ?: now())
                            ->maxDate(now()),
                        Select::make('dispatchStatus')
                            ->label('Dispatch status')
                            ->options([
                                'Requested' => 'Requested',
                                'Assigned' => 'Assigned',
                                'En Route' => 'En Route',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->multiple()
                            ->searchable(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            TripOverview::class,
            DispatchChart::class,   
            PassengerChart::class,
            VehicleOverview::class,
            LatestIncident::class,         
        ];
    }
}
