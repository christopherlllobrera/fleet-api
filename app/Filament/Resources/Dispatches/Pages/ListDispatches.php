<?php

namespace App\Filament\Resources\Dispatches\Pages;

use App\Filament\Resources\Dispatches\DispatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Dispatch;

class ListDispatches extends ListRecords
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'Assigned' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Assigned')),
            'Unassigned' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Unassigned')),
            'Unserved' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Unserved')),
            'Cancelled' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Cancelled')),
            'Completed' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Completed')),
        ];
    }
}
