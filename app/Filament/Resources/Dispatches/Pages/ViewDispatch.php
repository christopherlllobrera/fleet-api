<?php

namespace App\Filament\Resources\Dispatches\Pages;

use App\Filament\Resources\Dispatches\DispatchResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ViewDispatch extends Page
{
    use InteractsWithRecord;

    protected static string $resource = DispatchResource::class;

    protected string $view = 'filament.resources.dispatches.pages.dispatchingview';

    protected static string $routePath = 'dispatch-view';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
