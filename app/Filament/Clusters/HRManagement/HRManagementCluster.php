<?php

namespace App\Filament\Clusters\HRManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class HRManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBoxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'HR';

    protected static ?string $navigationLabel = 'HR Management';

    protected static ?string $clusterBreadcrumb = 'HR Management';
}
