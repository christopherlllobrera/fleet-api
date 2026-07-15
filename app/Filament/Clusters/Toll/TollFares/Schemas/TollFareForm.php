<?php

namespace App\Filament\Clusters\Toll\TollFares\Schemas;

use App\Models\TollPoint;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TollFareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Toll Road Details')
                    ->columns([
                        'default' => 1,
                        'lg' => 2,
                    ])
                    ->columnSpan(2)
                    ->description('Use this section to upload a new form and define its display details. Ensure the Title is descriptive. The Icon will help users quickly identify the form type.')
                    ->schema([
                        Select::make('toll_road_id')
                            ->relationship('tollRoad', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $set) {
                                $set('entry_point_id', null);
                                $set('exit_point_id', null);
                            }),
                        TextInput::make('fare')
                            ->required()
                            ->numeric(),
                        Select::make('entry_point_id')
                            ->label('Entry Point')
                            ->live()
                            ->options(function (callable $get) {
                                $tollRoadId = $get('toll_road_id');
                                if (! $tollRoadId) {
                                    return [];
                                }

                                return TollPoint::where('toll_road_id', $tollRoadId)
                                    ->whereIn('type', ['entry', 'both'])
                                    ->pluck('name', 'id');
                            })
                            ->required(),
                        Select::make('exit_point_id')
                            ->label('Exit Point')
                            ->live()
                            ->options(function (callable $get) {
                                $tollRoadId = $get('toll_road_id');
                                $entryPointId = $get('entry_point_id');
                                if (! $tollRoadId) {
                                    return [];
                                }
                                $query = TollPoint::where('toll_road_id', $tollRoadId)
                                    ->whereIn('type', ['exit', 'both']);
                                // Exclude the entry point if it's selected
                                if ($entryPointId) {
                                    $query->where('id', '!=', $entryPointId);
                                }

                                return $query->pluck('name', 'id');
                            })
                            ->required(),
                    ]),
                Section::make('Fare Matrix')
                    ->columnSpan(1)
                    ->description('')
                    ->schema([
                        Select::make('class')
                            ->label('Class')
                            ->options([
                                '1' => 'Class 1',
                                '2' => 'Class 2',
                                '3' => 'Class 3',
                            ])
                            ->required(),
                        TextInput::make('discount')
                            ->label('Discount (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }
}
