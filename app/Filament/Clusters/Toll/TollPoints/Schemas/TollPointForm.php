<?php

namespace App\Filament\Clusters\Toll\TollPoints\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TollPointForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Toll Point')
                    ->columnSpan(['lg' => 2])
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->options([
                                'entry' => 'Entry Point',
                                'exit' => 'Exit Point',
                                'both' => 'Entry & Exit',
                            ])
                            ->default('both')
                            ->required(),
                        TextInput::make('latitude')
                            ->required()
                            ->numeric(),
                        TextInput::make('longitude')
                            ->required()
                            ->numeric(),
                    ]),
                Section::make('Payment Methods')
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        CheckboxList::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'easytrip' => 'EasyTrip RFID',
                                'autosweep' => 'AutoSweep RFID',
                            ]),
                        Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }
}
