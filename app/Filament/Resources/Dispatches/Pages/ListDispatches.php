<?php

namespace App\Filament\Resources\Dispatches\Pages;

use App\Filament\Resources\Dispatches\DispatchResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDispatches extends ListRecords
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Dispatch'),
            ExportAction::make()
                ->label('Export')
                ->exporter(DispatchExporter::class)
                ->enableVisibleTableColumnsByDefault(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'Pending' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Pending')),
            'Assigned' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Assigned')),
            'En Route' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'En Route')),
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
