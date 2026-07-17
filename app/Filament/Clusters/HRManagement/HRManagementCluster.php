<?php

namespace App\Filament\Clusters\HRManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class HRManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBoxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'Workforce';

    protected static ?string $navigationLabel = 'Workforce Structure';

    protected static ?string $clusterBreadcrumb = 'Workforce Structure';

    protected static ?string $slug = 'workforce-structure';
}
