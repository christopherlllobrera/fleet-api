<?php

namespace App\Filament\Resources\Dispatches\RelationManagers;

use App\Models\VehiclePowerType;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleEnergyLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicleEnergyLogs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('reference_no')
                    ->label('Reference No (AWF/SI)')
                    ->required()
                    ->maxLength(255),

                Select::make('power_type_id')
                    ->label('Power/Fuel Type')
                    ->options(fn () => VehiclePowerType::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live(),

                DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->default(now()),

                TextInput::make('cost')
                    ->label('Total Cost')
                    ->numeric()
                    ->prefix('₱')
                    ->required(),

                FileUpload::make('attachment')
                    ->label('Upload Receipt')
                    ->disk('public')
                    ->directory('fuel_logs')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->maxSize(2048),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference_no')
            ->columns([
                TextColumn::make('reference_no')
                    ->label('Reference No')
                    ->searchable(),

                TextColumn::make('powerType.name')
                    ->label('Power Type')
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('cost')
                    ->label('Cost')
                    ->money('PHP')
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->headerActions([
                CreateAction::make()->label('Add Fuel Log'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
