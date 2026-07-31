<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Schemas;

use App\Models\CorrectiveWorkOrder;
use App\Models\Employee;
use App\Models\Vehicle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CorrectiveWorkOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Job Order Information')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Job Order Details')
                                ->description('Enter the job order reference numbers and billing information')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->collapsible()
                                ->schema([
                                    TextInput::make('job_order_no')
                                        ->label('Job Order No.')
                                        ->placeholder('e.g., CM-'.date('Y').'-00001') // CM-YYYY-XXXXX
                                        ->default(function () {
                                            $currentYear = date('Y');

                                            $latestJobOrder = CorrectiveWorkOrder::query()
                                                ->whereNotNull('job_order_no')
                                                ->where('job_order_no', 'like', 'CM-'.$currentYear.'-%')
                                                ->orderByDesc('job_order_no')
                                                ->first();

                                            if ($latestJobOrder?->job_order_no) {
                                                $lastNumber = (int) Str::afterLast($latestJobOrder->job_order_no, '-');

                                                return 'CM-'.$currentYear.'-'.str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
                                            }

                                            return 'CM-'.$currentYear.'-000001';
                                        })
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                    TextInput::make('job_order_sap_no')
                                        ->label('SAP Job Order No.')
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255)
                                        ->validationMessages([
                                            'unique' => 'The SAP Job Order No. has already been used.',
                                        ]),
                                    TextInput::make('billing_invoice_no')
                                        ->label('Billing Invoice No.')
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255)
                                        ->validationMessages([
                                            'unique' => 'The Invoice No. has already been used.',
                                        ]),
                                    TextInput::make('charge_account_no')
                                        ->label('Charge Account No.')
                                        ->placeholder('e.g., CA-MAINT-001')
                                        ->maxLength(255),
                                    Select::make('type')
                                        ->options([
                                            'Owned' => 'Owned',
                                            'Serviced' => 'Serviced',
                                        ])
                                        ->live(),
                                    Select::make('assignment')
                                        ->options([
                                            'Contracted' => 'Contracted',
                                            'In-house' => 'In-house',
                                            'Non-operational' => 'Non-operational',
                                            'Unassigned' => 'Unassigned',
                                        ])
                                        ->live()
                                        ->visible(fn (callable $get): bool => $get('type') === 'Owned'),
                                    TextInput::make('UCR_ref_no')
                                        ->label('UCR Ref. No.'),
                                    TextInput::make('UCR_amount')
                                        ->label('UCR Amount')
                                        ->numeric()
                                        ->prefix('₱'),
                                    TextInput::make('invoice')
                                        ->label('Invoice')
                                        ->numeric(),
                                    ToggleButtons::make('status')
                                        ->inline()
                                        ->options([
                                            'Pending' => 'Pending',
                                            'In Progress' => 'In Progress',
                                            
                                            'Completed' => 'Completed',
                                            'Cancelled' => 'Cancelled',
                                        ])
                                        ->colors([
                                            'Pending' => 'gray',
                                            'In Progress' => 'info',
                                          
                                            'Completed' => 'success',
                                            'Cancelled' => 'danger',
                                        ])
                                        ->icons([
                                            'Pending' => 'heroicon-o-information-circle',
                                            'In Progress' => 'heroicon-o-wrench-screwdriver',
                                          
                                            'Completed' => 'heroicon-o-check-circle',
                                            'Cancelled' => 'heroicon-o-x-circle',
                                        ])
                                        ->default('Pending')
                                        ->columnSpanFull(),
                                ])->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 3,
                                    'xl' => 3,
                                    '2xl' => 3,
                                ])
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 2,
                                    '2xl' => 2,
                                ]),
                            Section::make()
                                ->relationship('workOrder')
                                ->visible(fn (callable $get): bool => $get('assignment') === 'Contracted')
                                ->schema([
                                    Select::make('company_id')->label('Company')
                                        ->options([
                                            'MLI' => 'MLI',
                                            'MBI' => 'MBI',
                                            'MIESCOR' => 'MIESCOR',
                                        ]),
                                    DatePicker::make('start_date')->label('Start Date'),
                                    DatePicker::make('end_date')->label('End Date'),
                                    TextInput::make('contract_amount')
                                        ->numeric()->label('Contract Amount'),
                                    Select::make('contact_person_name')
                                        ->label('Contact Person')
                                        ->options(function (): array {
                                            return Employee::query()
                                                ->get()
                                                ->mapWithKeys(fn (Employee $employee) => [
                                                    $employee->full_name => $employee->full_name,
                                                ])
                                                ->all();
                                        })
                                        ->searchable()
                                        ->preload(),
                                    TextInput::make('contact_person_email')
                                        ->label('Email')
                                        ->email(),
                                    TextInput::make('contact_person_no')
                                        ->label('Contact No.')
                                        ->prefix('+63')
                                        ->numeric(),
                                    FileUpload::make('contracted_attachment')
                                        ->label('Contracted File Attachment')
                                        ->directory('vehicle_job_orders'),
                                ])->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 3,
                                    'xl' => 3,
                                    '2xl' => 3,
                                ])
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 3,
                                    'xl' => 3,
                                    '2xl' => 3,
                                ]),
                            Section::make('Vehicle Information')
                                ->description('Select the vehicle and provide location details. Vehicle type will be automatically populated based on the selected vehicle.')
                                ->icon('heroicon-o-truck')
                                ->schema([
                                    Select::make('plate_no_id')
                                    ->label('Vehicle Plate Number')
                                    ->relationship('plateNo', 'plate_no')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (callable $set, $state) {
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
                                    ->afterStateHydrated(function (callable $set, $state) {
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
                                    TextInput::make('vehicle_location')
                                        ->label('Vehicle Location')
                                        ->placeholder('e.g., MLI , Meralco')
                                        ->maxLength(255),
                                    TextInput::make('odometer_reading')
                                        ->label('Odometer Reading (km)')
                                    ->placeholder('e.g., 25000')
                                    // ->extraAttributes()
                                    ->numeric(),
                                ])
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 2,
                                    '2xl' => 2,
                                ])
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 2,
                                    '2xl' => 2,
                                ]),
                            Section::make('Driver and Contact Person')
                                ->description('Enter the details of the assigned driver, contact person, and the requisitioning office for this job order.')
                                ->icon('heroicon-o-users')
                                ->collapsible()
                                ->schema([
                                    Select::make('driver_name_id')
                                    ->label('Driver')
                                    ->relationship('driverName', 'employee_no')
                                    ->getOptionLabelFromRecordUsing(fn (Employee $record) => $record->full_name)
                                    ->searchable(['first_name', 'middle_name', 'last_name'])
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        if (! $state) {
                                            $set('Personnel No', null);
                                            $set('driver_contact_no', null);

                                            return;
                                        }
                                        $employee = Employee::with('contacts')->find($state);
                                        if ($employee) {
                                            $set('Personnel No', $employee->employee_no);
                                            $set('driver_contact_no', $employee->contacts->isNotEmpty() ? $employee->contacts->first()->value : 'N/A');
                                        }
                                    })
                                    ->afterStateHydrated(function (callable $set, $state) {
                                        if (! $state) {
                                            return;
                                        }
                                        $employee = Employee::with('contacts')->find($state);
                                        if ($employee) {
                                            $set('Personnel No', $employee->employee_no);
                                            $set('driver_contact_no', $employee->contacts->isNotEmpty() ? $employee->contacts->first()->value : 'N/A');
                                        }
                                    }),
                                    TextInput::make('Personnel No')
                                    ->label('Driver Employee ID')
                                    ->readOnly()
                                    ->dehydrated(false),
                                    TextInput::make('driver_contact_no')
                                    ->label('Driver Contact No.')
                                    ->readOnly()
                                    ->dehydrated(false),
                                    Select::make('requisition_office')
                                    ->label('Requisition Office')
                                    ->placeholder('e.g., MLI, MBI, MIESCOR, Individual, Others')
                                    ->options([
                                        'MLI' => 'MLI',
                                        'MBI' => 'MBI',
                                        'MIESCOR' => 'MIESCOR',
                                        'Individual' => 'Individual',
                                        'Others' => 'Others',
                                    ])
                                    ->suffixIcon('heroicon-o-building-office-2'),
                                    Select::make('contact_person_id')
                                    ->label('Contact Person')
                                    ->relationship('contactPerson', 'employee_no')
                                    ->getOptionLabelFromRecordUsing(fn (Employee $record) => $record->full_name)
                                    ->searchable(['first_name', 'middle_name', 'last_name'])
                                    ->preload()
                                    ->required()
                                    ->live(),
                                    TextInput::make('contact_number')
                                    ->label('Contact Number')
                                    ->placeholder('e.g., 09123456789')
                                    ->prefix('+63')
                                    ->numeric()
                                    ->maxLength(255)
                                    ->suffixIcon('heroicon-o-phone')
                                    ->dehydrated(false),

                                ])
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 2,
                                    '2xl' => 2,
                                ])->columnSpan([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2,
                            ]),
                        ]),
                    Step::make('Problem Assessment')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->schema([
                            Section::make('Trouble Report & Assessment')
                                ->description('Detailed documentation of vehicle problems and initial findings')
                                ->icon('heroicon-o-document-magnifying-glass')
                                ->collapsible()
                                ->schema([
                                    Textarea::make('vehicle_trouble_report')
                                    ->label('Vehicle Trouble Report')
                                    ->hint('To be filled by the driver')
                                    ->placeholder('Describe the vehicle problem in detail...')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->required(),
                                    Textarea::make('initial_assessment')
                                    ->label('Initial Assessment')
                                    ->hint('To be filled by the mechanic')
                                    ->required()
                                    ->placeholder('Initial diagnosis and recommended actions...')
                                    ->rows(4)
                                    ->columnSpanFull(),
                                ]),
                        ]),
                ])
                    ->columnSpanFull()
                    ->skippable(),
            ]);
    }
}
