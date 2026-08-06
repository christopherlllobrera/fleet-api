<?php

namespace App\Filament\Widgets;

use App\Models\Dispatch;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TripOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

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
        $startDate = filled($this->pageFilters['startDate'] ?? null)
            ? Carbon::parse($this->pageFilters['startDate'])
            : null;
        $endDate = filled($this->pageFilters['endDate'] ?? null)
            ? Carbon::parse($this->pageFilters['endDate'])
            : now();
        $dispatchStatus = $this->pageFilters['dispatchStatus'] ?? null;

        $dispatchQuery = Dispatch::query()
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->when(filled($dispatchStatus), fn ($q) => $q->whereIn('status', $dispatchStatus));

        $totalRequested = (clone $dispatchQuery)->count();
        $totalCompleted = (clone $dispatchQuery)->where('status', 'Completed')->count();
        $totalEnRoute = (clone $dispatchQuery)->where('status', 'En Route')->count();
        $totalAssigned = (clone $dispatchQuery)->where('status', 'Assigned')->count();

        // Monthly sparkline data for the last 7 months
        $months = collect(range(6, 0))->map(fn (int $ago) => Carbon::now()->subMonths($ago)->startOfMonth());

        $monthlyDispatches = Dispatch::query()
            ->where('created_at', '>=', $months->first())
            ->when(filled($dispatchStatus), fn ($q) => $q->whereIn('status', $dispatchStatus))
            ->get();

        $requestedChart = [];
        $completedChart = [];
        $enRouteChart = [];
        $assignedChart = [];

        foreach ($months as $month) {
            $monthKey = $month->format('Y-m');

            $monthDispatches = $monthlyDispatches->filter(fn (Dispatch $d): bool => $d->created_at?->format('Y-m') === $monthKey);

            $requestedChart[] = $monthDispatches->count();
            $completedChart[] = $monthDispatches->where('status', 'Completed')->count();
            $enRouteChart[] = $monthDispatches->where('status', 'En Route')->count();
            $assignedChart[] = $monthDispatches->where('status', 'Assigned')->count();
        }

        return [
            Stat::make('Trip Requested', (string) $totalRequested)
                ->description('Number of trips requested')
                ->descriptionIcon(Heroicon::ArrowPath)
                ->chart($requestedChart)
                ->color('info'),
            Stat::make('Trip Completed', (string) $totalCompleted)
                ->description('Number of trips served')
                ->descriptionIcon(Heroicon::CheckCircle)
                ->chart($completedChart)
                ->color('success'),
            Stat::make('En Route', (string) $totalEnRoute)
                ->description('Currently en route dispatched')
                ->descriptionIcon(Heroicon::ArrowTrendingUp)
                ->chart($enRouteChart)
                ->color('primary'),
            Stat::make('Assigned', (string) $totalAssigned)
                ->description('Currently assigned dispatched')
                ->descriptionIcon(Heroicon::ArchiveBoxArrowDown)
                ->chart($assignedChart)
                ->color('gray'),
        ];
    }
}
