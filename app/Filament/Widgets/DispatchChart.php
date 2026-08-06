<?php

namespace App\Filament\Widgets;

use App\Models\Dispatch;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class DispatchChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Dispatch Chart';

    protected static ?int $sort = 1;

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
            ->groupBy(fn (Dispatch $dispatch): string => $dispatch->created_at?->format('Y-m') ?? '')
            ->map(fn ($group) => $group->count());

        $labels = [];
        $data = [];

        foreach ($months as $month) {
            $labels[] = $month->format('M Y');
            $data[] = $dispatches->get($month->format('Y-m'), 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Dispatch created',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
