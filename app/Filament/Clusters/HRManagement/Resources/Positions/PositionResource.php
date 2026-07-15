<?php

namespace App\Filament\Clusters\HRManagement\Resources\Positions;

use App\Filament\Clusters\HRManagement\HRManagementCluster;
use App\Filament\Clusters\HRManagement\Resources\Positions\Pages\ManagePositions;
use App\Models\Position;
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

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = HRManagementCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('position_no')
                    ->label('Position No.')

                    ->unique(ignoreRecord: true)
                    ->required()
                    ->validationMessages([
                        'unique' => 'The position no already exists.',
                        'required' => 'The position no is required.',
                    ]),
                TextInput::make('position_description')
                    ->label('Position Description')
                    ->required()
                    ->validationMessages([
                        'required' => 'The position description is required.',
                    ]),
                Select::make('department_id')
                    ->label('Department ID')
                    ->relationship('department', 'department_description')
                    ->required()
                    ->validationMessages([
                        'required' => 'The department id is required.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('position_no')
                    ->label('Position No.')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('position_description')
                    ->label('Description')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('department.department_description')
                    ->label('Department')
                    ->sortable()
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
                    ->modalHeading('Edit Position')
                    ->modalWidth(Width::ExtraLarge),
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
            'index' => ManagePositions::route('/'),
        ];
    }
}
