<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Log;

class MaterialUseAction extends Action
{
    public static function make(?string $name = null): static
    {
        $name ??= 'materialsUsed';

        return parent::make($name)
            ->label('Materials Used')
            ->icon('heroicon-o-cube-transparent')
            ->color('warning')
            ->modalWidth(Width::FiveExtraLarge)
            ->modalHeading('Materials Used')
            ->modalDescription('Keep a detailed list of all materials, parts, and supplies applied to this job order.')
            ->form(self::getFormSchema())
            ->fillForm(fn ($record) => [
                'issuance_of_materials' => $record->issuance_of_materials ?? [],
                'return_of_materials' => $record->return_of_materials ?? [],
            ])
            ->modalSubmitActionLabel('Save Materials')
            ->action(fn (array $data, $record) => self::handle($data, $record));
    }

    protected static function getFormSchema(): array
    {
        return [
            Section::make('Materials Used')
                ->description('Keep a detailed list of all materials, parts, and supplies applied to this job order.')
                ->icon('heroicon-o-cube-transparent')
                ->collapsible()
                ->schema([
                    Repeater::make('issuance_of_materials')
                        ->defaultItems(0)
                        ->label('Issuance of Materials Used')
                        ->schema([
                            TextInput::make('stf_no')
                                ->label('STF No.')
                                ->placeholder('e.g., STF-2024-001')
                                ->maxLength(255),
                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->placeholder('e.g., 10')
                                ->numeric(),
                            Textarea::make('parts_description')
                                ->label('Parts Description')
                                ->placeholder('parts description...')
                                ->rows(2),
                        ])
                        ->columns(1)
                        ->itemLabel(fn (array $state): ?string => $state['stf_no'] ?? 'New Issuance of Materials Used')
                        ->addActionLabel('Add Issuance of Materials Used')
                        ->collapsible()
                        ->cloneable(),
                    Repeater::make('return_of_materials')
                        ->label('Return of Materials Used')
                        ->defaultItems(0)
                        ->schema([
                            TextInput::make('stf_no')
                                ->label('STF No.')
                                ->placeholder('e.g., STF-2024-001')
                                ->maxLength(255),
                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->placeholder('e.g., 10')
                                ->numeric(),
                            Textarea::make('parts_description')
                                ->label('Parts Description')
                                ->placeholder('parts description...')
                                ->rows(2),
                        ])
                        ->columns(1)
                        ->itemLabel(fn (array $state): ?string => $state['stf_no'] ?? 'New Return of Materials Used')
                        ->addActionLabel('Add Return of Materials Used')
                        ->collapsible()
                        ->cloneable(),
                ]),
        ];
    }

    protected static function handle(array $data, $record): void
    {
        try {
            $record->update([
                'issuance_of_materials' => $data['issuance_of_materials'] ?? [],
                'return_of_materials' => $data['return_of_materials'] ?? [],
            ]);

            Notification::make()
                ->title('Materials saved successfully!')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Log::error('MaterialUseAction failed: '.$e->getMessage());

            Notification::make()
                ->title('Error saving materials')
                ->danger()
                ->body('An error occurred: '.$e->getMessage())
                ->send();
        }
    }
}
