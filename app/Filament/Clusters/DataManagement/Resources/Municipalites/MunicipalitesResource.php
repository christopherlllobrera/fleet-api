<?php

namespace App\Filament\Clusters\DataManagement\Resources\Municipalites;

use App\Filament\Clusters\DataManagement\DataManagementCluster;
use App\Filament\Clusters\DataManagement\Resources\Municipalites\Pages\ManageMunicipalites;
use App\Models\Municipality;
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

class MunicipalitesResource extends Resource
{
    protected static ?string $model = Municipality::class;

    protected static ?int $navigationSort = 9;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = DataManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('province_id')
                    ->label('Province')
                    ->relationship('province', 'province_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('municipality_name')
                    ->label('Municipality/City')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('province.province_name')
                    ->label('Province')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('municipality_name')
                    ->label('Municipality/City')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth(Width::ExtraLarge)
                    ->modalHeading('Edit Municipality/City'),
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
            'index' => ManageMunicipalites::route('/'),
        ];
    }
}
