<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Drivers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Employee;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn (Employee $record) => $record->full_name)
                            ->searchable(['first_name', 'middle_name', 'last_name'])
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ]),
                        DatePicker::make('medical_expiry')
                            ->required(),
                        Select::make('country_id')
                            ->relationship('country', 'country_name')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnspan(['lg' => 2]),
                Section::make()
                    ->columns(1)
                    ->columnspan(['lg' => 1])
                    ->schema([
                        TextInput::make('license_no')
                            ->required(),
                        DatePicker::make('license_expiry')
                            ->required(),

                        Select::make('license_class')
                            ->options([
                                'Non-professional' => 'Non-professional',
                                'Professional' => 'Professional',
                            ]),

                    ]),
            ]);
    }
}
