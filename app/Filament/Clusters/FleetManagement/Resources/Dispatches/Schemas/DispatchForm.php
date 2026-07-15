<?php

namespace App\Filament\Clusters\FleetManagement\Resources\Dispatches\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use App\Models\RequestingOffice;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Employee;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Fahiem\FilamentPinpoint\Pinpoint;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

class DispatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Wizard::make([
                    Step::make('Dispatch Details')
                        ->schema([
                            Section::make('Location')
                                ->schema([
                                     Pinpoint::make('from_location')
                                        ->required()
                                        ->defaultZoom(500)
                                        ->height(400)
                                        ->draggable()
                                        ->searchable()
                                        ->defaultLocation(14.58989669180146, 121.06391716565099),
                                    Pinpoint::make('to_location')
                                        ->required()
                                        ->defaultZoom(20)
                                        ->height(400)
                                        ->draggable()
                                        ->searchable()
                                        ->defaultLocation(14.591645224717015, 121.06905452338266),
                                    
                                        ])
                                ->columns(2)
                                ->columnspan(2),
                                Section::make('Dispatch Information')
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
                                                    'Unserved' => 'warning',
                                                    'Cancelled' => 'danger',
                                                    'Completed' => 'success',
                                                ]),
                                        Radio::make('priority')
                                            ->label('Priority Level')->required()
                                            ->options([
                                                'Low' => 'Low',
                                                'Medium' => 'Medium',
                                                'High' => 'High',
                                            ])->default('Medium')
                                            ->inline()
                                            ->validationMessages([
                                                'required' => 'Priority level field is required.'
                                            ]),
                                        TextInput::make('ticket_no')
                                            ->label('Ticket No.')
                                            ->required(),
                                        DateTimePicker::make('departure_time')
                                            ->required(),
                                        Select::make('requesting_office_id')
                                            ->label('Requesting Office')
                                            ->options(
                                                RequestingOffice::all()->pluck('office_name', 'id')->toArray()
                                            ),
                                        Textarea::make('purpose')
                                            ->label('Purpose')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        
                                        
                                        
                                ])
                                ->columns(1)
                                ->columnSpan(1),
                           
                        ])
                        ->columns(3),
                    Step::make('Vehicle & Passenger Details')
                        ->columns(3)
                        ->schema([
                           Section::make()
                                ->columns(1)
                                ->columnSpan(['lg' => 1])
                                ->schema([
                                    Select::make('vehicle_id')
                                        ->label('Plate No.')
                                        ->options(
                                            Vehicle::all()->pluck('plate_no', 'id')->toArray()
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, ?string $state) {
                                            if (!$state) {
                                                $set('model', null);
                                                $set('maker_id', null);
                                                $set('year', null);
                                                $set('vehicle_power_type_id', null);
                                                return;
                                            }
                                            
                                            $vehicle = Vehicle::find($state);
                                            if ($vehicle) {
                                                $set('model', $vehicle->model);
                                                $set('maker_id', $vehicle->maker_id);
                                                $set('year', $vehicle->year);
                                                $set('vehicle_power_type_id', $vehicle->vehicle_power_type_id);
                                            }
                                        })
                                        ->afterStateHydrated(function (Set $set, ?string $state) {
                                            if (!$state) return;
                                            
                                            $vehicle = Vehicle::find($state);
                                            if ($vehicle) {
                                                $set('model', $vehicle->model);
                                                $set('maker_id', $vehicle->maker_id);
                                                $set('year', $vehicle->year);
                                                $set('vehicle_power_type_id', $vehicle->vehicle_power_type_id);
                                            }
                                        }),
                                    ]),
                            Section::make()
                                ->columns(2)
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
                                    Select::make('employee_id')
                                        ->label('Driver')
                                        ->options(
                                            Driver::all()->pluck('employee_id', 'id')->toArray()
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ,
                                    ]),
                            Section::make()
                                ->columns(2)
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
                        ]),
                ])
                ->skippable()
                ->columnSpanFull()
                ->submitAction(new HtmlString(Blade::render(
                <<<BLADE
                    <x-filament::button
                        type="submit"
                        size="sm">
                        Submit
                    </x-filament::button>
                BLADE))),
            ]);
    }
}
