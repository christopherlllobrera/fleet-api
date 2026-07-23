<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class DispatchChart extends ChartWidget
{
    protected ?string $heading = 'Dispatch Chart';

    protected function getData(): array
    {
        return [
             'datasets' => [
                [
                    'label' => 'Dispatch created',
                    'data' => [55, 62, 53, 47, 59, 67, 73, 80, 75, 85, 90, 95],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
