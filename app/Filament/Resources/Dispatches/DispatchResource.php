<?php

namespace App\Filament\Resources\Dispatches;

use App\Filament\Resources\Dispatches\Pages\CreateDispatch;
use App\Filament\Resources\Dispatches\Pages\EditDispatch;
use App\Filament\Resources\Dispatches\Pages\ListDispatches;
use App\Filament\Resources\Dispatches\Pages\ViewDispatch;
use App\Filament\Resources\Dispatches\Schemas\DispatchForm;
use App\Filament\Resources\Dispatches\Tables\DispatchesTable;
use App\Models\Dispatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DispatchResource extends Resource
{
    protected static ?string $model = Dispatch::class;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet';

    protected static ?string $navigationLabel = 'Dispatchings';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Dispatchings';
    // protected static ?string $slug = 'Dispatchings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Pending')->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of pending dispatches';
    }

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
            RelationManagers\VehicleEnergyLogsRelationManager::class,
            RelationManagers\TollsRelationManager::class,
            RelationManagers\IncidentsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDispatches::route('/'),
            'create' => CreateDispatch::route('/create'),
            'edit' => EditDispatch::route('/{record}/edit'),
            'view' => ViewDispatch::route('/{record}/dispatch-view'),
        ];
    }
}
