<?php

namespace App\Filament\Widgets;

use App\Models\Dispatch;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class PassengerChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Passenger Chart';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = filled($this->pageFilters['startDate'] ?? null)
            ? Carbon::parse($this->pageFilters['startDate'])->startOfMonth()
            : Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = filled($this->pageFilters['endDate'] ?? null)
            ? Carbon::parse($this->pageFilters['endDate'])->endOfMonth()
            : now();
        $dispatchStatus = $this->pageFilters['dispatchStatus'] ?? null;

        $months = collect();
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $months->push($cursor->copy());
            $cursor->addMonth();
        }

        $dispatches = Dispatch::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->when(filled($dispatchStatus), fn ($q) => $q->whereIn('status', $dispatchStatus))
            ->get()
            ->groupBy(fn (Dispatch $dispatch): string => $dispatch->created_at?->format('Y-m') ?? '');

        $labels = [];
        $data = [];

        foreach ($months as $month) {
            $labels[] = $month->format('M Y');
            $monthDispatches = $dispatches->get($month->format('Y-m'), collect());
            $data[] = $monthDispatches->sum('passenger_count');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Passengers transported',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
