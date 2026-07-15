<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Vehicles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(['lg' => 2])
                    ->columns(2)
                    ->schema([
                        Select::make('company_id')
                            ->relationship('company', 'name')
                            ->required(),
                        Select::make('business_unit_id')
                            ->relationship('business_unit', 'name')
                            ->required(),
                        TextInput::make('plate_no')
                            ->required()
                            ->maxLength(255),
                        Select::make('maker_id')
                            ->relationship('maker', 'name')
                            ->required(),
                        TextInput::make('model')
                            ->maxLength(255),
                        TextInput::make('year')
                            ->maxLength(4),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ]),
                    ]),
                Section::make()
                    ->columnSpan(['lg' => 1])
                    ->columns(1)
                    ->schema([
                        Select::make('vehicle_category_id')
                            ->relationship('vehicleCategory', 'name')
                            ->required(),
                        Select::make('vehicle_power_type_id')
                            ->relationship('vehiclePowerType', 'name')
                            ->required(),
                        Select::make('vehicle_group_id')
                            ->relationship('vehicleGroup', 'name')
                            ->required(),
                    ]),
            ]);
    }
}
