<?php

namespace App\Filament\Clusters\DataManagement\Resources\Provinces;

use App\Filament\Clusters\DataManagement\DataManagementCluster;
use App\Filament\Clusters\DataManagement\Resources\Provinces\Pages\ManageProvinces;
use App\Models\Province;
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

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 8;

    protected static ?string $cluster = DataManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('region_id')
                    ->label('Region')
                    ->relationship('region', 'region_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabledOn(['edit']),
                TextInput::make('province_name')
                    ->label('Province Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('province_name')
                    ->label('Province')
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
                    ->modalWidth(Width::Large)
                    ->modalHeading('Edit Province'),
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
            'index' => ManageProvinces::route('/'),
        ];
    }
}
