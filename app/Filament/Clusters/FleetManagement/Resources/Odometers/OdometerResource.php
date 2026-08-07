<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Odometers;

use App\Filament\Clusters\FleetManagement\FleetManagementCluster;
use App\Filament\Clusters\FleetManagement\Resources\Odometers\Pages\ManageOdometers;
use App\Models\Odometer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OdometerResource extends Resource
{
    protected static ?string $model = Odometer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = FleetManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('dispatch_id')
                    ->label('Ticket No.')
                    ->relationship('dispatch', 'ticket_no')
                    ->required(),
                Select::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship('vehicle', 'plate_no')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('odometer_in')
                    ->required()
                    ->numeric(),
                TextInput::make('odometer_out')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dispatch.ticket_no')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vehicle.plate_no')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('odometer_in')
                    ->searchable(),
                TextColumn::make('odometer_out')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOdometers::route('/'),
        ];
    }
}
