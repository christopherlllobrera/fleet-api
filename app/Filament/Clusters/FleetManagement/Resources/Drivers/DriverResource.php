<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Drivers;

use App\Filament\Clusters\FleetManagement\FleetManagementCluster;
use App\Filament\Clusters\FleetManagement\Resources\Drivers\Pages\CreateDriver;
use App\Filament\Clusters\FleetManagement\Resources\Drivers\Pages\EditDriver;
use App\Filament\Clusters\FleetManagement\Resources\Drivers\Pages\ListDrivers;
use App\Filament\Clusters\FleetManagement\Resources\Drivers\Schemas\DriverForm;
use App\Filament\Clusters\FleetManagement\Resources\Drivers\Tables\DriversTable;
use App\Models\Driver;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $cluster = FleetManagementCluster::class;

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DriverForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DriversTable::configure($table);
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
            'index' => ListDrivers::route('/'),
            'create' => CreateDriver::route('/create'),
            'edit' => EditDriver::route('/{record}/edit'),
        ];
    }
}
