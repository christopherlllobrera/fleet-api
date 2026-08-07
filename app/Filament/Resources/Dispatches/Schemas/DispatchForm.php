<?php

namespace App\Filament\Resources\Dispatches\Schemas;

use App\Models\Dispatch;
use App\Models\Driver;
use App\Models\RequestingOffice;
use App\Models\Vehicle;
use Fahiem\FilamentPinpoint\Pinpoint;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class DispatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Wizard::make([
                    Step::make('Dispatch Details')
                        ->icon('heroicon-o-map')
                        ->schema([
                            Section::make('Location')
                                ->icon('heroicon-o-map-pin')
                                ->description('Set the pickup and drop-off points for your trip. Accurate locations help ensure smooth dispatching.')
                                ->schema([
                                    Pinpoint::make('from_map')
                                        ->provider('leaflet')
                                        ->label('From Location')
                                        ->latField('from_lat')
                                        ->lngField('from_lng')
                                        ->addressField('from_location')
                                        ->defaultZoom(15)
                                        ->height(400)
                                        ->draggable()
                                        ->searchable()
                                        ->defaultLocation(14.58989669180146, 121.06391716565099),
                                    Pinpoint::make('to_map')
                                        ->provider('leaflet')
                                        ->label('To Location')
                                        ->latField('to_lat')
                                        ->lngField('to_lng')
                                        ->addressField('to_location')
                                        ->defaultZoom(15)
                                        ->height(400)
                                        ->draggable()
                                        ->searchable()
                                        ->defaultLocation(14.591645224717015, 121.06905452338266),
                                    Hidden::make('from_location')->label('From'),
                                    Hidden::make('to_location')->label('To'),
                                    Hidden::make('from_lat')->label('From Latitude'),
                                    Hidden::make('to_lat')->label('To Latitude'),
                                    Hidden::make('from_lng')->label('From Longitude'),
                                    Hidden::make('to_lng')->label('To Longitude'),
                                    Textarea::make('purpose')
                                        ->label('Purpose')
                                        ->rows(2)
                                        ->placeholder('Enter the purpose of the trip (optional)')
                                        ->columnSpanFull(),

                                ])
                                ->columns(2)
                                ->columnspan(2),
                            Section::make('Dispatch Information')
                                ->description('Enter the request item and VEA ticket number')
                                ->icon('heroicon-o-ticket')
                                ->schema([
                                    ToggleButtons::make('status')
                                        ->inline()
                                        ->options([
                                            'Pending' => 'Pending',
                                            'Assigned' => 'Assigned',
                                            'Unassigned' => 'Unassigned',
                                            'Unserved' => 'Unserved',
                                            'Cancelled' => 'Cancelled',
                                            'Completed' => 'Completed',
                                        ])
                                        ->colors([
                                            'Pending' => 'gray',
                                            'Assigned' => 'info',
                                            'Unassigned' => 'primary',
                                            'Unserved' => 'info',
                                            'Cancelled' => 'danger',
                                            'Completed' => 'success',
                                        ])
                                        ->default('Pending'),
                                    Radio::make('priority_level')
                                        ->label('Priority Level')->required()
                                        ->options([
                                            'Low' => 'Low',
                                            'Medium' => 'Medium',
                                            'High' => 'High',
                                        ])->default('Medium')
                                        ->inline()
                                        ->validationMessages([
                                            'required' => 'Priority level field is required.',
                                        ]),
                                    TextInput::make('ticket_no')
                                        ->label('Ticket No.')
                                        ->placeholder('Enter the VEA number manually')
                                        ->required(),
                                    TextInput::make('request_item')
                                        ->label('Request Item')
                                        ->placeholder('Enter the request item manually')
                                        ->required(),
                                    TextInput::make('passenger_count')
                                        ->label('Passenger Count')
                                        ->numeric()
                                        ->required()
                                        ->placeholder('Enter number of passengers (optional)')
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $count = (int) $state;

                                            if ($count <= 0) {
                                                $set('passengers', []);

                                                return;
                                            }

                                            // Get existing passengers
                                            $existingPassengers = $get('passengers') ?? [];
                                            $existingCount = count($existingPassengers);

                                            if ($count > $existingCount) {
                                                // Add more empty passenger entries
                                                for ($i = $existingCount; $i < $count; $i++) {
                                                    $existingPassengers[] = [
                                                        'passenger_name' => '',
                                                        'passenger_contact_no' => '',
                                                        'passenger_pick_up_location' => '',
                                                    ];
                                                }
                                            } elseif ($count < $existingCount) {
                                                // Trim excess passengers
                                                $existingPassengers = array_slice($existingPassengers, 0, $count);
                                            }

                                            $set('passengers', array_values($existingPassengers));
                                        })
                                        ->validationMessages([
                                            'numeric' => 'Passenger count must be a number.',
                                        ])
                                        ->live(),
                                    DateTimePicker::make('departure_time')
                                        ->required(),
                                    Select::make('requesting_office_id')
                                        ->label('Requesting Office')
                                        ->searchable()
                                        ->preload()
                                        ->options(
                                            RequestingOffice::all()->pluck('office_name', 'id')->toArray()
                                        ),

                                ])
                                ->columns(1)
                                ->columnSpan(1),
                        ])
                        ->columns(3),
                    Step::make('Vehicle & Passenger Details')
                        ->icon('heroicon-o-identification')
                        ->columns(3)
                        ->schema([
                            Section::make()
                                ->columns(1)
                                ->columnSpan(['lg' => 1])
                                ->schema([
                                    Select::make('vehicle_id')
                                        ->label('Plate No.')
                                        ->options(function (Get $get, ?string $state): array {
                                            $options = Vehicle::query()
                                                ->where('vehicle_group_id', '!=', 4)
                                                ->orderBy('plate_no')
                                                ->pluck('plate_no', 'id')
                                                ->toArray();

                                            $departureTime = $get('departure_time');

                                            if ($departureTime) {
                                                $selectedDateTime = Carbon::parse($departureTime);
                                                $start = $selectedDateTime->copy()->subHour();
                                                $end = $selectedDateTime->copy()->addHour();

                                                $conflictingVehicleIds = Dispatch::query()
                                                    ->whereNotIn('status', ['Cancelled', 'Completed'])
                                                    ->whereNotNull('departure_time')
                                                    ->where(function (Builder $query) use ($selectedDateTime, $start, $end): void {
                                                        $query
                                                            ->whereDate('departure_time', $selectedDateTime->toDateString())
                                                            ->orWhereBetween('departure_time', [$start, $end]);
                                                    })
                                                    ->pluck('vehicle_id')
                                                    ->filter()
                                                    ->unique()
                                                    ->values();

                                                foreach ($conflictingVehicleIds as $vehicleId) {
                                                    unset($options[$vehicleId]);
                                                }
                                            }

                                            if ($state && ! isset($options[$state])) {
                                                $vehicle = Vehicle::find($state);

                                                if ($vehicle) {
                                                    $options[$state] = $vehicle->plate_no;
                                                }
                                            }

                                            return $options;
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, ?string $state) {
                                            if (! $state) {
                                                $set('model', null);
                                                $set('maker_id', null);
                                                $set('year', null);
                                                $set('vehicle_power_type_id', null);

                                                return;
                                            }

                                            $vehicle = Vehicle::with(['maker', 'vehiclePowerType'])->find($state);
                                            if ($vehicle) {
                                                $set('model', $vehicle->model);
                                                $set('maker_id', $vehicle->maker ? $vehicle->maker->name : null);
                                                $set('year', $vehicle->year);
                                                $set('vehicle_power_type_id', $vehicle->vehiclePowerType ? $vehicle->vehiclePowerType->name : null);
                                            }
                                        })
                                        ->afterStateHydrated(function (Set $set, ?string $state) {
                                            if (! $state) {
                                                return;
                                            }

                                            $vehicle = Vehicle::with(['maker', 'vehiclePowerType'])->find($state);
                                            if ($vehicle) {
                                                $set('model', $vehicle->model);
                                                $set('maker_id', $vehicle->maker ? $vehicle->maker->name : null);
                                                $set('year', $vehicle->year);
                                                $set('vehicle_power_type_id', $vehicle->vehiclePowerType ? $vehicle->vehiclePowerType->name : null);
                                            }
                                        }),
                                ]),
                            Section::make('Vehicle Information')
                                ->icon('heroicon-o-truck')
                                ->columns(2)
                                ->description('The information here is auto-filled')
                                ->columnSpan(['lg' => 2])
                                ->schema([
                                    TextInput::make('model')
                                        ->label('Model')
                                        ->readOnly()
                                        ->dehydrated(false),
                                    TextInput::make('maker_id')
                                        ->label('Maker')
                                        ->readOnly()
                                        ->dehydrated(false),
                                    TextInput::make('year')
                                        ->label('Year')
                                        ->readOnly()
                                        ->dehydrated(false),
                                    TextInput::make('vehicle_power_type_id')
                                        ->label('Vehicle Power Type')
                                        ->readOnly()
                                        ->dehydrated(false),
                                ]),
                            Section::make()
                                ->columns(1)
                                ->columnSpan(['lg' => 1])
                                ->schema([
                                    Select::make('driver_id')
                                        ->label('Driver')
                                        ->options(
                                            Driver::with('employee')->get()->pluck('full_name', 'id')->toArray()
                                        )
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $driver = Driver::with('employee.contacts')->find($state);
                                            if ($driver) {
                                                $set('contact_no', $driver->contact_no);
                                                $set('Personnel No', $driver->personnel_no);
                                            }
                                        })
                                        ->afterStateHydrated(function ($state, callable $set) {
                                            $driver = Driver::with('employee.contacts')->find($state);
                                            if ($driver) {
                                                $set('contact_no', $driver->contact_no);
                                                $set('Personnel No', $driver->personnel_no);
                                            }
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live(),
                                ]),
                            Section::make('Driver Information')
                                ->icon('heroicon-o-user')
                                ->description('The information here is auto-filled')
                                ->columns(2)
                                ->columnSpan(['lg' => 2])
                                ->schema([
                                    TextInput::make('Personnel No')
                                        ->label('Employee ID')
                                        ->readOnly()
                                        ->dehydrated(false),
                                    TextInput::make('contact_no')
                                        ->label('Contact No.')
                                        ->readOnly()
                                        ->dehydrated(false),
                                ]),
                            Section::make('Passenger Information')
                                ->description('Enter passenger information')
                                ->icon('heroicon-o-identification')
                                ->columns(3)
                                ->columnspan(['lg' => 3])
                                ->schema([
                                    Repeater::make('passengers')
                                        ->relationship('passengers')
                                        ->schema([
                                            TextInput::make('name')
                                                ->required(),
                                            TextInput::make('contact_no')
                                                ->label('Contact No.')
                                                ->prefix('+63')
                                                ->numeric()
                                                ->minLength(10)
                                                ->maxLength(10),
                                            Pinpoint::make('pick_up_map')
                                                ->provider('leaflet')
                                                ->label('Pick Up Location')
                                                ->latField('pick_up_lat')
                                                ->lngField('pick_up_lng')
                                                ->addressField('pick_up_location')
                                                // ->required()
                                                ->columnSpanFull()
                                                ->defaultZoom(15)
                                                ->height(400)
                                                ->draggable()
                                                ->searchable()
                                                ->defaultLocation(14.58989669180146, 121.06391716565099),

                                            Grid::make([
                                                'lg' => 3,
                                            ])
                                                ->schema([
                                                    Hidden::make('pick_up_location')->label('Location'),
                                                    Hidden::make('pick_up_lat')->label('Latitude'),
                                                    Hidden::make('pick_up_lng')->label('Longitude'),
                                                ]),

                                        ])
                                        ->columnSpanFull()
                                        ->columns(2),
                                ]),
                        ]),
                ])
                    ->skippable()
                    ->columnSpanFull()
                    ->submitAction(new HtmlString(Blade::render(
                        <<<'BLADE'
                    <x-filament::button
                        type="submit"
                        size="sm">
                        Submit
                    </x-filament::button>
                BLADE))),
            ]);
    }
}
