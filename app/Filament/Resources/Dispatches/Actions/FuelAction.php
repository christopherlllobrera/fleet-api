<?php

namespace App\Filament\Resources\Dispatches\Actions;

// use App\Models\Dispatch; // Using dynamic $record injection
use App\Models\Toll;
use App\Models\TollPoint;
use App\Models\TollRoad;
use App\Models\VehicleEnergyLogs;
use App\Models\VehiclePowerType;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Log;

class FuelAction extends Action
{
    public static function make(?string $name = null): static
    {
        $name ??= 'fuel_and_toll';

        return parent::make($name)
            ->label('Fuel & Toll')
            ->icon('heroicon-o-funnel')
            ->color('primary')
            ->requiresConfirmation()
            ->size(Size::Large)
            ->modalWidth(Width::FiveExtraLarge)
            ->modalHeading('Fuel and Toll Entry')
            ->modalDescription('Please provide fuel consumption and toll details for this dispatch.')
            ->steps(self::getSteps())
            ->modalSubmitActionLabel('Save All Details')
            ->action(fn (array $data, $record) => self::handle($data, $record))
            ->fillForm(fn (array $data, $record) => self::hydrateForm($data, $record));
    }

    protected static function getSteps(): array
    {
        return [

            Step::make('Fuel Consumption')
                ->description('Record fuel or energy usage for this trip.')
                // ->skippable()
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                ])
                ->schema([
                    TextInput::make('reference_no')
                        ->label('Reference No (AWF/SI)')
                        ->placeholder('Authorization to Withdraw Fuel / Sales Invoice')
                        ->required(),

                    Select::make('power_type_id')
                        ->label('Power/Fuel Type')
                        ->options(fn () => VehiclePowerType::pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('cost')
                        ->label('Total Cost')
                        ->numeric()
                        ->prefix('₱')
                        ->required(),

                    FileUpload::make('fuel_attachment')
                        ->label('Upload Fuel Receipt')
                        ->disk('public')
                        ->directory('fuel_logs')
                        ->visibility('public')
                        ->preserveFilenames()
                        ->maxSize(2048)
                        ->columnSpanFull(),
                ]),

            Step::make('Toll Details')
                ->description('Add expressway and toll points manually.')
                // ->skippable()
                ->schema([
                    Text::make('Click the delete button if no tolls were used for this trip.')
                        ->color('neutral'),
                    Repeater::make('toll_entries')
                        ->label('Toll Entries')
                        ->columnSpanFull()
                        ->columns([
                            'default' => 1,
                            'sm' => 2,
                        ])
                        ->schema([
                            Select::make('toll_road_id')
                                ->label('Expressway')
                                ->options(fn () => TollRoad::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('entry_point_id', null))
                                ->afterStateUpdated(fn (Set $set) => $set('exit_point_id', null)),

                            Select::make('vehicle_class')
                                ->label('Vehicle Class')
                                ->options([
                                    'Class 1' => 'Class 1 (Cars, SUVs)',
                                    'Class 2' => 'Class 2 (Buses, Trucks)',
                                    'Class 3' => 'Class 3 (Large Trucks)',
                                ])
                                ->default('Class 1')
                                ->required(),

                            Select::make('entry_point_id')
                                ->label('Entry Point')
                                ->options(function (Get $get) {
                                    $tollRoadId = $get('toll_road_id');
                                    if (! $tollRoadId) {
                                        return [];
                                    }

                                    return TollPoint::where('toll_road_id', $tollRoadId)
                                        ->where('is_active', true)
                                        ->whereIn('type', ['entry', 'both'])
                                        ->orderBy('name')
                                        ->pluck('name', 'id');
                                })
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('exit_point_id', null)),

                            Select::make('exit_point_id')
                                ->label('Exit Point')
                                ->options(function (Get $get) {
                                    $tollRoadId = $get('toll_road_id');
                                    $entryPointId = $get('entry_point_id');
                                    if (! $tollRoadId || ! $entryPointId) {
                                        return [];
                                    }

                                    return TollPoint::where('toll_road_id', $tollRoadId)
                                        ->where('is_active', true)
                                        ->whereIn('type', ['exit', 'both'])
                                        ->where('id', '!=', $entryPointId)
                                        ->orderBy('name')
                                        ->pluck('name', 'id');
                                })
                                ->required()
                                ->live(),

                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options([
                                    'Cash' => 'Cash',
                                    'RFID' => 'RFID',
                                    'Fleet Card' => 'Fleet Card',
                                ])
                                ->default('Cash')
                                ->required(),

                            TextInput::make('toll_fare')
                                ->label('Toll Fare')
                                ->numeric()
                                ->prefix('₱')
                                ->required(),

                            FileUpload::make('toll_attachments')
                                ->label('Attach Receipt')
                                ->disk('public')
                                ->directory('toll_attachments')
                                ->visibility('public')
                                ->multiple()
                                ->maxFiles(3)
                                ->preserveFilenames()
                                ->imageEditor()
                                ->columnSpanFull(),
                        ])

                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => isset($state['toll_road_id'])
                                ? TollRoad::find($state['toll_road_id'])?->name.' - ₱'.($state['toll_fare'] ?? '0.00')
                                : null
                        )
                        ->addActionLabel('Add Expressway Entry'),
                ]),
        ];
    }

    protected static function hydrateForm(array $data, $record): array
    {
        // Get the most recent fuel log for this dispatch
        $fuelLog = VehicleEnergyLogs::where('dispatch_id', $record->id)
            ->latest()
            ->first();

        // Get all toll entries for this dispatch
        $tollEntries = Toll::where('dispatch_id', $record->id)
            ->get()
            ->map(function ($toll) {
                return [
                    'toll_road_id' => $toll->toll_road_id,
                    'vehicle_class' => $toll->vehicle_class,
                    'entry_point_id' => $toll->entry_point_id,
                    'exit_point_id' => $toll->exit_point_id,
                    'payment_method' => $toll->payment_method,
                    'toll_fare' => $toll->toll_fare,
                    'toll_attachments' => $toll->toll_attachments,
                ];
            })
            ->toArray();

        return [
            'reference_no' => $fuelLog?->reference_no,
            'power_type_id' => $fuelLog?->power_type_id,
            'cost' => $fuelLog?->cost,
            'fuel_attachment' => $fuelLog?->attachment,
            'date' => $fuelLog?->date ? (is_string($fuelLog->date) ? $fuelLog->date : $fuelLog->date->format('Y-m-d')) : null,
            'toll_entries' => $tollEntries,
        ];
    }

    protected static function handle(array $data, $record): void
    {
        try {
            // 1. Handle Fuel/Energy Log (create or update)
            $fuelLog = VehicleEnergyLogs::updateOrCreate(
                [
                    'dispatch_id' => $record->id,
                    'reference_no' => $data['reference_no'] ?? null,
                ],
                [
                    'vehicle_id' => $record->vehicle_id,
                    'power_type_id' => $data['power_type_id'] ?? null,
                    'date' => $data['date'] ?? now(),
                    'cost' => $data['cost'] ?? 0,
                    'attachment' => $data['fuel_attachment'] ?? null,
                ]
            );

            // 2. Handle Toll Entries (delete existing and recreate)
            Toll::where('dispatch_id', $record->id)->delete();

            if (! empty($data['toll_entries'])) {
                foreach ($data['toll_entries'] as $entry) {
                    Toll::create([
                        'dispatch_id' => $record->id,
                        'toll_road_id' => $entry['toll_road_id'] ?? null,
                        'vehicle_class' => $entry['vehicle_class'] ?? null,
                        'entry_point_id' => $entry['entry_point_id'] ?? null,
                        'exit_point_id' => $entry['exit_point_id'] ?? null,
                        'payment_method' => $entry['payment_method'] ?? null,
                        'toll_fare' => $entry['toll_fare'] ?? 0,
                        'toll_attachments' => $entry['toll_attachments'] ?? null,
                    ]);
                }
            }

            Notification::make()
                ->title('Fuel and Toll details saved successfully!')
                ->success()
                ->send();

        } catch (\Throwable $e) {
            Log::error('FuelAction failed: '.$e->getMessage());

            Notification::make()
                ->title('Error saving details')
                ->danger()
                ->body('An error occurred: '.$e->getMessage())
                ->send();
        }
    }
}
