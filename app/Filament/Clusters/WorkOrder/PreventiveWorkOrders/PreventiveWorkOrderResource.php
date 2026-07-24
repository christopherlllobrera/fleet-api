<?php

namespace App\Filament\Clusters\WorkOrder\PreventiveWorkOrders;

use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Pages\CreatePreventiveWorkOrder;
use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Pages\EditPreventiveWorkOrder;
use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Pages\ListPreventiveWorkOrders;
use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Schemas\PreventiveWorkOrderForm;
use App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Tables\PreventiveWorkOrdersTable;
use App\Models\PreventiveWorkOrder;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Clusters\WorkOrder\WorkOrderCluster;

class PreventiveWorkOrderResource extends Resource
{
    protected static ?string $model = PreventiveWorkOrder::class;

    protected static string | UnitEnum | null $navigationGroup = 'Work Order';

    protected static ?string $cluster = WorkOrderCluster::class;

    protected static ?string $navigationLabel = 'Preventive Work Order';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Preventive Work Order';

    protected static ?string $slug = 'preventive-work-order';

    public static function form(Schema $schema): Schema
    {
        return PreventiveWorkOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PreventiveWorkOrdersTable::configure($table);
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
            'index' => ListPreventiveWorkOrders::route('/'),
            'create' => CreatePreventiveWorkOrder::route('/create'),
            'edit' => EditPreventiveWorkOrder::route('/{record}/edit'),
        ];
    }
}
