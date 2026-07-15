<?php

namespace App\Filament\Clusters\Settings\Permissions;

use App\Filament\Clusters\Settings\Permissions\Pages\CreatePermission;
use App\Filament\Clusters\Settings\Permissions\Pages\EditPermission;
use App\Filament\Clusters\Settings\Permissions\Pages\ListPermissions;
use App\Filament\Clusters\Settings\Permissions\Schemas\PermissionForm;
use App\Filament\Clusters\Settings\Permissions\Tables\PermissionsTable;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Permission as ModelsPermission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = ModelsPermission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Key;

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $navigationLabel = 'Permission';

    protected static ?int $navigationSort = 3;

    protected static ?string $breadcrumb = 'Permission';

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
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
            'index' => ListPermissions::route('/'),
            // 'create' => CreatePermission::route('/create'),
            // 'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
