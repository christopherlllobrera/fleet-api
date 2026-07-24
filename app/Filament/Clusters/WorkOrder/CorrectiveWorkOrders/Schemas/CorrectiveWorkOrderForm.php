<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use App\Models\CorrectiveWorkOrder;
use App\Models\Employee;

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
                                        ->placeholder('e.g., CM-' . date('Y') . '-00001') //CM-YYYY-XXXXX
                                        ->default(function () {
                                            $currentYear = date('Y');

                                            $latestJobOrder = CorrectiveWorkOrder::query()
                                                ->whereNotNull('job_order_no')
                                                ->where('job_order_no', 'like', 'CM-' . $currentYear . '-%')
                                                ->orderByDesc('job_order_no')
                                                ->first();

                                            if ($latestJobOrder?->job_order_no) {
                                                $lastNumber = (int) Str::afterLast($latestJobOrder->job_order_no, '-');

                                                return 'CM-' . $currentYear . '-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
                                            }

                                            return 'CM-' . $currentYear . '-000001';
                                        })
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                    TextInput::make('job_order_sap_no')
                                        ->label('SAP Job Order No.')
                                        ->unique()
                                        ->maxLength(255),
                                    TextInput::make('billing_invoice_no')
                                        ->label('Billing Invoice No.')
                                        ->unique()
                                        ->maxLength(255),
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
                                    TextInput::make('contact_person_email'
                                        )->label('Email')->email(),
                                    TextInput::make('contact_person_no')
                                        ->label('Contact No.')->prefix('+63')->numeric(),
                                    FileUpload::make('contracted_attachment')
                                        ->label('Contracted File Attachment')
                                        ->columnSpanFull()->directory('vehicle_job_orders'),
                                ]) ->columns([
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
                                                if (!$state) {
                                                    $set('model', null);
                                                    $set('maker_id', null);
                                                    $set('year', null);
                                                    $set('vehicle_power_type_id', null);
                                                    return;
                                                }

                                                $vehicle = \App\Models\Vehicle::with(['maker', 'vehiclePowerType'])->find($state);
                                                if ($vehicle) {
                                                    $set('model', $vehicle->model);
                                                    $set('maker_id', $vehicle->maker ? $vehicle->maker->name : null);
                                                    $set('year', $vehicle->year);
                                                    $set('vehicle_power_type_id', $vehicle->vehiclePowerType ? $vehicle->vehiclePowerType->name : null);
                                                }
                                            })
                                            ->afterStateHydrated(function (callable $set, $state) {
                                                if (!$state) return;

                                                $vehicle = \App\Models\Vehicle::with(['maker', 'vehiclePowerType'])->find($state);
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
                                            if (!$state) {
                                                $set('Personnel No', null);
                                                $set('driver_contact_no', null);
                                                return;
                                            }
                                            $employee = \App\Models\Employee::with('contacts')->find($state);
                                            if ($employee) {
                                                $set('Personnel No', $employee->employee_no);
                                                $set('driver_contact_no', $employee->contacts->isNotEmpty() ? $employee->contacts->first()->value : 'N/A');
                                            }
                                        })
                                        ->afterStateHydrated(function (callable $set, $state) {
                                            if (!$state) return;
                                            $employee = \App\Models\Employee::with('contacts')->find($state);
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
                            Step::make('Work Details')
                            ->icon('heroicon-o-wrench')
                            // ->description('Record actual work performed and time spent')
                            ->schema([
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
                                            ->itemLabel(fn (array $state): ?string =>
                                                $state['work_type'] ? ucfirst(str_replace('_', ' ', $state['work_type'])) : null
                                            )
                                            ->addActionLabel('Add Work Time Entry')
                                            ->defaultItems(1)
                                            ->collapsible()
                                            ->cloneable(),
                                    ]),
                                    Section::make('Materials Used')
                                        ->description('Keep a detailed list of all materials, parts, and supplies applied to this job order.')
                                        ->icon('heroicon-o-cube-transparent')
                                        ->collapsible()
                                        ->schema([
                                            Repeater::make('issuance_of_materials')
                                                ->defaultItems(1)
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
                                                ->itemLabel(fn (array $state): ?string =>
                                                    $state['stf_no'] ?? 'New Issuance of Materials Used'
                                                )
                                                ->addActionLabel('Add Issuance of Materials Used')
                                                ->collapsible()
                                                ->cloneable(),
                                            Repeater::make('return_of_materials')
                                                ->label('Return of Materials Used')
                                                ->defaultItems(1)
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
                                                ->itemLabel(fn (array $state): ?string =>
                                                    $state['stf_no'] ?? 'New Return of Materials Used'
                                                )
                                                ->addActionLabel('Add Return of Materials Used')
                                                ->collapsible()
                                                ->cloneable(),
                                    ]),
                            ]),


                ])
                ->columnSpanFull()
                ->skippable()
            ]);
    }
}
