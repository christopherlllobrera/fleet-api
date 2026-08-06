<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use App\Models\Dispatch;
use App\Models\CorrectiveWorkOrder;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VehicleOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'sm' => 2,
        'md' => 4,
        'xl' => 5,
    ];

    public function getColumns(): int|array
    {
        return 4;
    }

    protected function getStats(): array
    {
        $totalVehicles = Vehicle::count();

        // Available vehicles: not in dispatch with status 'assigned' or 'en route'
        $availableVehicles = Vehicle::whereDoesntHave('dispatches', function ($query) {
            $query->whereIn('status', ['assigned', 'en route']);
        })->count();

        // En route vehicles: in dispatch with status 'en route'
        $enRouteVehicles = Vehicle::whereHas('dispatches', function ($query) {
            $query->where('status', 'en route');
        })->count();

        // Maintenance vehicles: have corrective work orders
        $maintenanceVehicles = Vehicle::whereHas('correctiveWorkOrders')->count();

        return [
            Stat::make('Total Vehicles', (string) $totalVehicles)
                ->description('Number of vehicles')
                ->descriptionIcon(Heroicon::ArrowTrendingUp)
                ->color('info'),
            Stat::make('Available Vehicles', (string) $availableVehicles)
                ->description('Number of available vehicles')
                ->descriptionIcon(Heroicon::CheckCircle)
                ->color('success'),
            Stat::make('En Route', (string) $enRouteVehicles)
                ->description('Currently en route vehicles')
                ->descriptionIcon(Heroicon::ArrowTrendingUp)
                ->color('primary'),
            Stat::make('Maintenance', (string) $maintenanceVehicles)
                ->description('Currently in maintenance')
                ->descriptionIcon(Heroicon::ArchiveBoxArrowDown)
                ->color('gray'),
        ];
    }
}
