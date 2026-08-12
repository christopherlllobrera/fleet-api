<?php

namespace App\Filament\Widgets;

use App\Models\Dispatch;
use App\Models\Vehicle;
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

        // Available vehicles: has device_sn, not en route, and not in corrective work orders
        $availableVehicles = Vehicle::query()
            ->whereNotNull('device_sn')
            ->where('device_sn', '!=', '')
            ->whereDoesntHave('dispatches', function ($query) {
                $query->where('status', 'en route');
            })
            ->whereDoesntHave('correctiveWorkOrders')
            ->count();

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
