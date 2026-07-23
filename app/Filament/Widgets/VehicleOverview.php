<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class VehicleOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Vehicles', '192')
                ->description('Number of vehicles')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('info')
                ->chart([0, 2]),
            Stat::make('Available Vehicles', '77')
                ->description('Number of available vehicles')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::After)
                ->color('success')
                ->chart([0, 3]),
            Stat::make('En Route', '7')
                ->description('Currently en route vehicles')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('primary')
                ->chart([0, 5]),
            Stat::make('Maintenance', '15')
                ->description('Currently in maintenance')
                ->descriptionIcon('heroicon-o-archive-box-arrow-down', IconPosition::After)
                ->color('gray')
                ->chart([0, 7]),
        ];
    }
}
