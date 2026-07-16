<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Incidents;

use App\Filament\Clusters\FleetManagement\FleetManagementCluster;
use App\Filament\Clusters\FleetManagement\Resources\Incidents\Pages\CreateIncident;
use App\Filament\Clusters\FleetManagement\Resources\Incidents\Pages\EditIncident;
use App\Filament\Clusters\FleetManagement\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Clusters\FleetManagement\Resources\Incidents\Schemas\IncidentForm;
use App\Filament\Clusters\FleetManagement\Resources\Incidents\Tables\IncidentsTable;
use App\Models\Incident;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncidentResource extends Resource
{
    protected static ?string $model = Incident::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $cluster = FleetManagementCluster::class;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return IncidentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncidentsTable::configure($table);
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
            'index' => ListIncidents::route('/'),
            'create' => CreateIncident::route('/create'),
            'edit' => EditIncident::route('/{record}/edit'),
        ];
    }
}
