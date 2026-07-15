<?php

namespace App\Filament\Clusters\DataManagement\Resources\Barangays;

use App\Filament\Clusters\DataManagement\DataManagementCluster;
use App\Filament\Clusters\DataManagement\Resources\Barangays\Pages\ManageBarangays;
use App\Models\Barangay;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BarangayResource extends Resource
{
    protected static ?string $model = Barangay::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 10;

    protected static ?string $cluster = DataManagementCluster::class;

    protected static ?string $recordTitleAttribute = 'no';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('municipality_id')
                    ->required()
                    ->label('Municipality/City')
                    ->relationship('municipality', 'municipality_name')
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
                TextInput::make('barangay_name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('municipality.province.province_name')
                    ->label('Province')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('municipality.municipality_name')
                    ->label('Municipality/City')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('barangay_name')
                    ->label('Barangay')
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
                EditAction::make()
                    ->modalWidth(Width::ExtraLarge)
                    ->modalHeading('Edit Barangay'),
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
            'index' => ManageBarangays::route('/'),
        ];
    }
}
