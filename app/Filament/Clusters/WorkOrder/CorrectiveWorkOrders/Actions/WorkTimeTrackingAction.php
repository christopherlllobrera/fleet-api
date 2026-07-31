<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Log;

class WorkTimeTrackingAction extends Action
{
    public static function make(?string $name = null): static
    {
        $name ??= 'workTimeTracking';

        return parent::make($name)
            ->label('Work Time Tracking')
            ->icon('heroicon-o-clock')
            ->color('info')
            ->modalWidth(Width::FiveExtraLarge)
            ->modalHeading('Work Time Tracking')
            ->modalDescription('Track time spent on different work activities for this job order.')
            ->form(self::getFormSchema())
            ->fillForm(fn ($record) => [
                'actual_work_time' => $record->actual_work_time ?? [],
            ])
            ->modalSubmitActionLabel('Save Work Time')
            ->action(fn (array $data, $record) => self::handle($data, $record));
    }

    protected static function getFormSchema(): array
    {
        return [
            Section::make('Work Time Tracking')
                ->description('Track time spent on different work activities')
                ->icon('heroicon-o-clock')
                ->collapsible()
                ->schema([
                    Repeater::make('actual_work_time')
                        ->label('Actual Work Done')
                        ->schema([
                            Select::make('work_type')
                                ->label('Work Type')
                                ->options([
                                    'diagnosis' => 'Diagnosis',
                                    'repair' => 'Repair Work',
                                    'maintenance' => 'Maintenance',
                                    'testing' => 'Testing',
                                    'inspection' => 'Inspection',
                                    'parts_replacement' => 'Parts Replacement',
                                    'cleaning' => 'Cleaning',
                                    'other' => 'Other',
                                ])
                                ->required()
                                ->native(false),
                            DatePicker::make('date')
                                ->label('Date')
                                ->required()
                                ->default(now()),
                            TimePicker::make('start_time')
                                ->label('Start Time')
                                ->default(now())
                                ->required()
                                ->seconds(false),
                            TimePicker::make('end_time')
                                ->label('End Time')
                                ->required()
                                ->seconds(false),
                            TextInput::make('technician_name')
                                ->label('Technician/Mechanic')
                                ->placeholder('Name of person who performed the work')
                                ->maxLength(255),
                            Textarea::make('work_description')
                                ->label('Work Description or Remarks')
                                ->placeholder('Describe the work performed...')
                                ->rows(2),
                        ])
                        ->columns(1)
                        ->itemLabel(fn (array $state): ?string => ($state['work_type'] ?? null)
                            ? ucfirst(str_replace('_', ' ', $state['work_type']))
                            : null)
                        ->addActionLabel('Add Work Time Entry')
                        ->defaultItems(0)
                        ->collapsible()
                        ->cloneable(),
                ]),
        ];
    }

    protected static function handle(array $data, $record): void
    {
        try {
            $record->update([
                'actual_work_time' => $data['actual_work_time'] ?? [],
            ]);

            Notification::make()
                ->title('Work time entries saved successfully!')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Log::error('MaterialUSedAction failed: '.$e->getMessage());

            Notification::make()
                ->title('Error saving work time entries')
                ->danger()
                ->body('An error occurred: '.$e->getMessage())
                ->send();
        }
    }
}
