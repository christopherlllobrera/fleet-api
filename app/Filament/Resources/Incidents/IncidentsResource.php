<?php

namespace App\Filament\Resources\Incidents;

use App\Filament\Resources\Incidents\Pages\CreateIncidents;
use App\Filament\Resources\Incidents\Pages\EditIncidents;
use App\Filament\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Resources\Incidents\Schemas\IncidentsForm;
use App\Filament\Resources\Incidents\Tables\IncidentsTable;
use App\Models\Incident;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IncidentsResource extends Resource
{
    protected static ?string $model = Incident::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet';

    protected static ?string $navigationLabel = 'Incidents';

    protected static ?int $navigationSort = 2;

    protected static ?string $breadcrumb = 'Incidents';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Open')->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of open incidents';
    }

    public static function form(Schema $schema): Schema
    {
        return IncidentsForm::configure($schema);
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
            'create' => CreateIncidents::route('/create'),
            'edit' => EditIncidents::route('/{record}/edit'),
        ];
    }
}
