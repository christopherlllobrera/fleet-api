<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Odometers;

use App\Filament\Clusters\FleetManagement\FleetManagementCluster;
use App\Filament\Clusters\FleetManagement\Resources\Odometers\Pages\CreateOdometer;
use App\Filament\Clusters\FleetManagement\Resources\Odometers\Pages\EditOdometer;
use App\Filament\Clusters\FleetManagement\Resources\Odometers\Pages\ListOdometers;
use App\Filament\Clusters\FleetManagement\Resources\Odometers\Schemas\OdometerForm;
use App\Filament\Clusters\FleetManagement\Resources\Odometers\Tables\OdometersTable;
use App\Models\Odometer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OdometerResource extends Resource
{
    protected static ?string $model = Odometer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $cluster = FleetManagementCluster::class;

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return OdometerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OdometersTable::configure($table);
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
            'index' => ListOdometers::route('/'),
            'create' => CreateOdometer::route('/create'),
            'edit' => EditOdometer::route('/{record}/edit'),
        ];
    }
}
