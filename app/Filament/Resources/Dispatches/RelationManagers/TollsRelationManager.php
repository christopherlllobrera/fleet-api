<?php

namespace App\Filament\Resources\Dispatches\RelationManagers;

use App\Models\TollPoint;
use App\Models\TollRoad;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TollsRelationManager extends RelationManager
{
    protected static string $relationship = 'tolls';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('toll_road_id')
                    ->label('Expressway')
                    ->options(fn () => TollRoad::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->live(),

                Select::make('vehicle_class')
                    ->label('Vehicle Class')
                    ->options([
                        'Class 1' => 'Class 1 (Cars, SUVs)',
                        'Class 2' => 'Class 2 (Buses, Trucks)',
                        'Class 3' => 'Class 3 (Large Trucks)',
                    ])
                    ->default('Class 1')
                    ->required(),

                Select::make('entry_point_id')
                    ->label('Entry Point')
                    ->options(function (Get $get) {
                        $tollRoadId = $get('toll_road_id');
                        if (! $tollRoadId) {
                            return [];
                        }

                        return TollPoint::where('toll_road_id', $tollRoadId)
                            ->where('is_active', true)
                            ->whereIn('type', ['entry', 'both'])
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->live(),

                Select::make('exit_point_id')
                    ->label('Exit Point')
                    ->options(function (Get $get) {
                        $tollRoadId = $get('toll_road_id');
                        $entryPointId = $get('entry_point_id');
                        if (! $tollRoadId || ! $entryPointId) {
                            return [];
                        }

                        return TollPoint::where('toll_road_id', $tollRoadId)
                            ->where('is_active', true)
                            ->whereIn('type', ['exit', 'both'])
                            ->where('id', '!=', $entryPointId)
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->live(),

                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'Cash' => 'Cash',
                        'RFID' => 'RFID',
                        'Fleet Card' => 'Fleet Card',
                    ])
                    ->default('Cash')
                    ->required(),

                TextInput::make('toll_fare')
                    ->label('Toll Fare')
                    ->numeric()
                    ->prefix('₱')
                    ->required(),

                FileUpload::make('toll_attachments')
                    ->label('Attach Receipt')
                    ->disk('public')
                    ->directory('toll_attachments')
                    ->visibility('public')
                    ->multiple()
                    ->maxFiles(3)
                    ->preserveFilenames()
                    ->imageEditor(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('toll_road_id')
            ->columns([
                TextColumn::make('tollRoad.name')
                    ->label('Expressway')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('vehicle_class')
                    ->label('Vehicle Class')
                    ->sortable(),

                TextColumn::make('entryPoint.name')
                    ->label('Entry Point')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('exitPoint.name')
                    ->label('Exit Point')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable(),

                TextColumn::make('toll_fare')
                    ->label('Toll Fare')
                    ->money('PHP')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make()->label('Add Toll'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
