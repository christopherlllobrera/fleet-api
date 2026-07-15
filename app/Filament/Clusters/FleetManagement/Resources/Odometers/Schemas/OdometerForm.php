<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Odometers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OdometerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('dispatch_id')
                    ->required()
                    ->numeric(),
                TextInput::make('vehicle_id')
                    ->required()
                    ->numeric(),
                TextInput::make('odometer_in')
                    ->required(),
                TextInput::make('odometer_out')
                    ->required(),
            ]);
    }
}
