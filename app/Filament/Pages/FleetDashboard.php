<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DispatchChart;
use App\Filament\Widgets\LatestIncident;
use App\Filament\Widgets\PassengerChart;
use App\Filament\Widgets\TripOverview;
use App\Filament\Widgets\VehicleOverview;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

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
                                'Pending' => 'Pending',
                                'Assigned' => 'Assigned',
                                'Unassigned' => 'Unassigned',
                                'Unserved' => 'Unserved',
                                'Cancelled' => 'Cancelled',
                                'Completed' => 'Completed', 4,
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
