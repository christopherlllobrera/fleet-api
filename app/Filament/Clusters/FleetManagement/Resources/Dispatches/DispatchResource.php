<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Dispatches;

use App\Filament\Clusters\FleetManagement\FleetManagementCluster;
use App\Filament\Clusters\FleetManagement\Resources\Dispatches\Pages\CreateDispatch;
use App\Filament\Clusters\FleetManagement\Resources\Dispatches\Pages\EditDispatch;
use App\Filament\Clusters\FleetManagement\Resources\Dispatches\Pages\ListDispatches;
use App\Filament\Clusters\FleetManagement\Resources\Dispatches\Schemas\DispatchForm;
use App\Filament\Clusters\FleetManagement\Resources\Dispatches\Tables\DispatchesTable;
use App\Models\Dispatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DispatchResource extends Resource
{
    protected static ?string $model = Dispatch::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = FleetManagementCluster::class;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DispatchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispatchesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDispatches::route('/'),
            'create' => CreateDispatch::route('/create'),
            'edit' => EditDispatch::route('/{record}/edit'),
        ];
    }
}
