<?php

namespace App\Filament\Pages;

use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
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

    // public function filtersForm(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             Section::make('Find Events by Date')
    //                 ->icon('heroicon-o-funnel')
    //                 ->collapsible()
    //                 ->schema([
    //                     DatePicker::make('from'),
    //                     DatePicker::make('to'),
    //                 ])
    //                 ->columns(2)
    //                 ->columnspanfull(),
    //         ]);
    // }

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
