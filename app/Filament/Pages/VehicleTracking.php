<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class VehicleTracking extends Page
{
    protected string $view = 'filament.pages.vehicle-tracking';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $title = 'Vehicle Tracking';

    protected static string $routePath = 'live-positions';

    protected static string|UnitEnum|null $navigationGroup = 'Fleet';

    protected static ?int $navigationSort = 7;
}
