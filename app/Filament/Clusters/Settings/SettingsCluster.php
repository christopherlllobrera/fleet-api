<?php

namespace App\Filament\Clusters\Settings;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SettingsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    // protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';
}
