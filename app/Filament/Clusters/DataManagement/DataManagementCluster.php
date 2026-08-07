<?php

namespace App\Filament\Clusters\DataManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class DataManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Data';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('superadmin') ?? false;
    }
}
