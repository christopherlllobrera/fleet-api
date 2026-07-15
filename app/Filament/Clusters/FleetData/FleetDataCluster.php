<?php

namespace App\Filament\Clusters\FleetData;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class FleetDataCluster extends Cluster
{
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet';

    protected static ?int $navigationSort = 3;
}
