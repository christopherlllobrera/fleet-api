<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class TripOverview extends StatsOverviewWidget
{

    protected ?string $heading = 'Trip Overview';
    protected ?string $description = 'An overview of Dispatching.';
    protected ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'sm' => 2,
        'md' => 4,
        'xl' => 5,
    ];

    public function getColumns(): int | array
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Trip Requested', '192')
                ->description('Number of trips requested')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('info')
                ->chart([0, 2]),
            Stat::make('Trip Completed', '77')
                ->description('Number of trips served')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::After)
                ->color('success')
                ->chart([0, 3]),
            Stat::make('En Route', '7')
                ->description('Currently en route dispatched')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::After)
                ->color('primary')
                ->chart([0, 5]),
            Stat::make('Assignned', '15')
                ->description('Currently assigned dispatched')
                ->descriptionIcon('heroicon-o-archive-box-arrow-down', IconPosition::After)
                ->color('gray')
                ->chart([0, 7]),
        ];
    }
}
