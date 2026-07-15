<?php

namespace App\Filament\Clusters\DataManagement\Resources\Regions;

use App\Filament\Clusters\DataManagement\DataManagementCluster;
use App\Filament\Clusters\DataManagement\Resources\Regions\Pages\ManageRegions;
use App\Models\Region;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 7;

    protected static ?string $cluster = DataManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('region_name')
                    ->label('Region')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'required' => 'The region name is required.',
                        'unique' => 'The region name already exists.',
                    ]),
                TextInput::make('region_description')
                    ->label('Description')
                    ->required()
                    ->belowContent(Schema::between([
                        Flex::make([
                            Icon::make(Heroicon::InformationCircle)
                                ->grow(false),
                            'e.g. National Capital Region',
                        ]),
                    ]))
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('region_name')
                    ->label('Region')
                    ->searchable(),
                TextColumn::make('region_description')
                    ->label('Description')
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
                    ->modalWidth(Width::TwoExtraLarge)
                    ->modalHeading('Edit Region'),
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
            'index' => ManageRegions::route('/'),
        ];
    }
}
