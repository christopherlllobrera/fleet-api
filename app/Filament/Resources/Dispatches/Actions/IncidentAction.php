<?php

namespace App\Filament\Resources\Dispatches\Actions;

use App\Models\Incident;
use App\Models\Vehicle;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Log;

class IncidentAction extends Action
{
    public static function make(?string $name = null): static
    {
        $name ??= 'incident';

        return parent::make($name)
            ->label('Report Incident')
            ->icon('heroicon-o-exclamation-triangle')
            ->color('danger')
            ->requiresConfirmation()
            ->size(Size::Large)
            ->modalWidth(Width::FiveExtraLarge)
            ->modalHeading('Report Incident')
            ->modalDescription('Please provide details about the incident for this dispatch.')
            ->form(self::getFormSchema())
            ->modalSubmitActionLabel('Submit Report')
            ->action(fn (array $data, $record) => self::handle($data, $record))
            ->fillForm(fn (array $data, $record) => self::hydrateForm($data, $record));
    }

    protected static function getFormSchema(): array
    {
        return [
            Grid::make(['lg' => 3])
                ->schema([
                    Section::make('Incident Information')
                        ->description('This section contains information about the incident.')
                        ->icon('heroicon-o-phone')
                        ->columns(2)
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('company_id')
                                ->label('Company No.')
                                ->default(fn ($record) => $record->vehicle->company_id ?? null)
                                ->hidden(),

                            TextInput::make('reference_no')
                                ->label('Reference No.')
                                ->hidden(),

                            TextInput::make('dispatch_no')
                                ->label('Dispatch No.')
                                ->default(fn ($record) => $record->ticket_no)
                                ->disabled()
                                ->required(),

                            TextInput::make('vehicle_plate_no')
                                ->label('Vehicle Plate No.')
                                ->default(fn ($record) => $record->vehicle->plate_no ?? null)
                                ->disabled()
                                ->required(),

                            TextInput::make('reported_by')
                                ->label('Reported By')
                                ->required(),

                            DatePicker::make('reported_at')
                                ->label('Reported Date')
                                ->default(now())
                                ->required(),

                            TextInput::make('location')
                                ->label('Location')
                                ->required(),

                            Textarea::make('description')
                                ->label('Description')
                                ->rows(8)
                                ->columnSpanFull()
                                ->required(),
                        ]),

                    Section::make('Incident Classification')
                        ->description('This section contains information about the classification of the incident.')
                        ->icon('heroicon-o-information-circle')
                        ->columns(1)
                        ->columnSpan(1)
                        ->grow(false)
                        ->schema([
                            Select::make('incident_severity')
                                ->label('Severity')
                                ->options([
                                    'Low' => 'Low',
                                    'Minor' => 'Minor',
                                    'Major' => 'Major',
                                    'Critical' => 'Critical',
                                ])
                                ->required(),

                            Select::make('priority')
                                ->label('Priority')
                                ->options([
                                    'Urgent' => 'Urgent',
                                    'High' => 'High',
                                    'Normal' => 'Normal',
                                    'Low' => 'Low',
                                ])
                                ->required(),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'Open' => 'Open',
                                    'In Progress' => 'In Progress',
                                    'Resolved' => 'Resolved',
                                    'Closed' => 'Closed',
                                    'Cancelled' => 'Cancelled',
                                ])
                                ->default('Open')
                                ->required(),

                            Select::make('type')
                                ->label('Type')
                                ->options([
                                    'Vehicle Condition' => 'Vehicle Condition',
                                    'Vehicle Breakdown' => 'Vehicle Breakdown',
                                    'Timely Provision of Service' => 'Timely Provision of Service',
                                    'Timely Submission of Service Reports' => 'Timely Submission of Service Reports',
                                    'Availability of Resource' => 'Availability of Resource',
                                    'Overall Safety Performance' => 'Overall Safety Performance',
                                    'Business Ethics' => 'Business Ethics',
                                    'Trips Declined' => 'Trips Declined',
                                    'Other' => 'Other',
                                ])
                                ->required(),

                            FileUpload::make('attachments')
                                ->label('Attachments')
                                ->disk('public')
                                ->directory('incident_attachments')
                                ->visibility('public')
                                ->multiple()
                                ->maxFiles(5)
                                ->preserveFilenames()
                                ->columnSpanFull(),
                        ]),
                ]),
        ];
    }

    protected static function hydrateForm(array $data, $record): array
    {
        // Get the most recent incident for this dispatch
        $incident = Incident::where('dispatch_id', $record->id)
            ->latest()
            ->first();

        if (! $incident) {
            return [];
        }

        return [
            'company_id' => $incident->company_id,
            'reference_no' => $incident->reference_no,
            'dispatch_no' => $record->ticket_no,
            'vehicle_plate_no' => $record->vehicle->plate_no ?? null,
            'reported_by' => $incident->reported_by,
            'reported_at' => $incident->reported_at ? (is_string($incident->reported_at) ? $incident->reported_at : $incident->reported_at->format('Y-m-d')) : null,
            'location' => $incident->location,
            'description' => $incident->description,
            'incident_severity' => $incident->incident_severity,
            'priority' => $incident->priority,
            'status' => $incident->status,
            'type' => $incident->type,
            'attachments' => $incident->attachments,
        ];
    }

    protected static function handle(array $data, $record): void
    {
        try {
            // Check if there's an existing incident for this dispatch
            $existingIncident = Incident::where('dispatch_id', $record->id)
                ->latest()
                ->first();

            if ($existingIncident) {
                // Update existing incident
                $existingIncident->update([
                    'company_id' => $data['company_id'] ?? $record->vehicle->company_id ?? null,
                    'reference_no' => $data['reference_no'] ?? null,
                    'dispatch_id' => $record->id,
                    'vehicle_id' => $record->vehicle_id,
                    'reported_by' => $data['reported_by'] ?? null,
                    'reported_at' => $data['reported_at'] ?? now(),
                    'location' => $data['location'] ?? null,
                    'description' => $data['description'] ?? null,
                    'incident_severity' => $data['incident_severity'] ?? null,
                    'priority' => $data['priority'] ?? null,
                    'status' => $data['status'] ?? 'Open',
                    'type' => $data['type'] ?? null,
                    'attachments' => $data['attachments'] ?? null,
                ]);

                Notification::make()
                    ->title('Incident updated successfully!')
                    ->success()
                    ->send();
            } else {
                // Create new incident
                Incident::create([
                    'company_id' => $data['company_id'] ?? $record->vehicle->company_id ?? null,
                    'reference_no' => $data['reference_no'] ?? null,
                    'dispatch_id' => $record->id,
                    'vehicle_id' => $record->vehicle_id,
                    'reported_by' => $data['reported_by'] ?? null,
                    'reported_at' => $data['reported_at'] ?? now(),
                    'location' => $data['location'] ?? null,
                    'description' => $data['description'] ?? null,
                    'incident_severity' => $data['incident_severity'] ?? null,
                    'priority' => $data['priority'] ?? null,
                    'status' => $data['status'] ?? 'Open',
                    'type' => $data['type'] ?? null,
                    'attachments' => $data['attachments'] ?? null,
                ]);

                Notification::make()
                    ->title('Incident reported successfully!')
                    ->success()
                    ->send();
            }

        } catch (\Throwable $e) {
            Log::error('IncidentAction failed: '.$e->getMessage());

            Notification::make()
                ->title('Error reporting incident')
                ->danger()
                ->body('An error occurred: '.$e->getMessage())
                ->send();
        }
    }
}
