<?php

namespace App\Filament\Clusters\HRManagement\Resources\Employees\Schemas;

use App\Models\BachelorDegree;
use App\Models\Barangay;
use App\Models\Country;
use App\Models\DoctorateDegree;
use App\Models\MasteralDegree;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Region;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Personal Info')
                        ->schema([
                            TextInput::make('employee_no')
                                ->label('Employee No.')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->disabled(fn ($record) => $record !== null),
                            TextInput::make('first_name')
                                ->label('First Name')
                                ->required()
                                ->rules(['regex:/^[a-zA-Z ]+$/'])
                                ->validationMessages(['regex' => 'Numbers are not allowed']),
                            TextInput::make('middle_name')
                                ->label('Middle Name')
                                ->rules(['regex:/^[a-zA-Z ]+$/'])
                                ->validationMessages(['regex' => 'Numbers are not allowed']),
                            TextInput::make('last_name')
                                ->label('Last Name')
                                ->required()
                                ->rules(['regex:/^[a-zA-Z ]+$/'])
                                ->validationMessages(['regex' => 'Numbers are not allowed']),

                            Select::make('profile.suffix_name')
                                ->label('Suffix Name')
                                ->default('N/A')
                                ->options([
                                    'N/A' => 'N/A',
                                    'Sr' => 'Sr',
                                    'Jr' => 'Jr',
                                    'III' => 'III',
                                    'IV' => 'IV',
                                    'V' => 'V',
                                ]),
                            DatePicker::make('profile.date_of_birth')
                                ->label('Date of Birth'),
                            TextInput::make('profile.place_of_birth')
                                ->label('Place of Birth')
                                ->placeholder('City/Municipality'),
                            Select::make('profile.nationality_id')
                                ->label('Nationality')
                                ->relationship('profile.nationality', 'nationality')
                                ->searchable()
                                ->preload()
                                ->default(1),
                            TextInput::make('profile.personal_number')
                                ->label('Personal Number')
                                ->prefix('+63')
                                ->prefixIcon('heroicon-o-phone')
                                ->unique(ignoreRecord: true),
                            ToggleButtons::make('profile.gender')
                                ->label('Gender')
                                ->options([
                                    'Male' => 'Male',
                                    'Female' => 'Female',
                                ])
                                ->colors([
                                    'Male' => 'info',
                                    'Female' => 'danger',
                                ])
                                ->required()
                                ->inline(),
                            ToggleButtons::make('profile.civil_status')
                                ->label('Civil Status')
                                ->options([
                                    'Single' => 'Single',
                                    'Married' => 'Married',
                                    'Widowed/Widower' => 'Widowed/Widower',
                                    'Separated' => 'Separated',
                                ])
                                ->columnspan(2)
                                ->inline()
                                ->colors([
                                    'Single' => 'info',
                                    'Married' => 'success',
                                    'Widowed' => 'warning',
                                    'Separated' => 'danger',
                                ])
                                ->required()
                                ->live(),
                            FileUpload::make('birth_certificate')
                                ->label('Birth Certificate')
                                ->columnSpanFull()
                                ->multiple()
                                ->directory('documents/birth_certificates')
                                ->acceptedFileTypes(['image/*', 'application/pdf'])
                                ->maxSize(50000),
                        ])
                        ->columns([
                            'sm' => 1,
                            'md' => 2,
                            'lg' => 3]),
                    Step::make('Employment')
                        ->schema([
                            Select::make('company_id')
                                ->label('Company')
                                ->relationship('company', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('department_id')
                                ->label('Department')
                                ->relationship('department', 'department_description')
                                ->searchable()
                                ->preload(),
                            Select::make('position_id')
                                ->label('Position')
                                ->relationship('position', 'position_description')
                                ->searchable()
                                ->preload(),
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true),
                            DatePicker::make('date_hired')
                                ->label('Date Hired')
                                ->required(),
                            DatePicker::make('regularization_date')
                                ->label('Regularization Date'),
                            Select::make('employee_group')
                                ->label('Employee Group')
                                ->options([
                                    'Regular' => 'Regular',
                                    'Probitionary Contract' => 'Probitionary',
                                    'Regular Work Pool' => 'Regular Work Pool',
                                    'Contractual' => 'Contractual',
                                    'Project Hire' => 'Project Hire',
                                    'Fixed Term' => 'Fixed Term',
                                    'Service Agreement' => 'Service Agreement',
                                ]),
                            Checkbox::make('is_active')
                                ->label('Is Active')
                                ->default(true)
                                ->columnStart(1),
                        ])
                        ->columns([
                            'sm' => 1,
                            'md' => 2,
                            'lg' => 3,
                        ]),
                    Step::make('Contact Info')
                        ->schema([
                            Select::make('permanent_country_id')
                                ->label('Country')
                                ->options(Country::pluck('country_name', 'id'))
                                ->default(167)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('permanent_region_id', null);
                                    $set('permanent_province_id', null);
                                    $set('permanent_municipality_id', null);
                                    $set('permanent_barangay_id', null);
                                }),
                            Select::make('permanent_region_id')
                                ->label('Region')
                                ->options(fn (Get $get): Collection => Region::query()
                                    ->pluck('region_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->requiredIf('permanent_country_id', 167)
                                ->afterStateUpdated(function (Set $set) {
                                    $set('permanent_province_id', null);
                                    $set('permanent_municipality_id', null);
                                    $set('permanent_barangay_id', null);
                                }),
                            Select::make('permanent_province_id')
                                ->label('Province')
                                ->options(fn (Get $get): Collection => Province::query()
                                    ->where('region_id', $get('permanent_region_id'))
                                    ->pluck('province_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->disabled(fn (Get $get) => ! filled($get('permanent_region_id')))
                                ->afterStateUpdated(function (Set $set) {
                                    $set('permanent_municipality_id', null);
                                    $set('permanent_barangay_id', null);
                                }),
                            Select::make('permanent_municipality_id')
                                ->label('City/Municipality')
                                ->options(fn (Get $get): Collection => Municipality::query()
                                    ->where('province_id', $get('permanent_province_id'))
                                    ->pluck('municipality_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->disabled(fn (Get $get) => ! filled($get('permanent_province_id')))
                                ->afterStateUpdated(function (Set $set) {
                                    $set('permanent_barangay_id', null);
                                }),
                            Select::make('permanent_barangay_id')
                                ->label('Barangay')
                                ->options(fn (Get $get): Collection => Barangay::query()
                                    ->where('municipality_id', $get('permanent_municipality_id'))
                                    ->pluck('barangay_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get) => ! filled($get('permanent_municipality_id'))),
                            Textarea::make('permanent_address')
                                ->label('Address Details')
                                ->placeholder('House/Unit Number, Street Name, Subdivision')
                                ->columnSpanFull(),
                            Checkbox::make('is_same_as_permanent')
                                ->label('Same as Permanent Address')
                                ->live()
                                ->columnSpanFull()
                                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                    if ($state) {
                                        $set('present_country_id', $get('permanent_country_id'));
                                        $set('present_region_id', $get('permanent_region_id'));
                                        $set('present_province_id', $get('permanent_province_id'));
                                        $set('present_municipality_id', $get('permanent_municipality_id'));
                                        $set('present_barangay_id', $get('permanent_barangay_id'));
                                        $set('present_address', $get('permanent_address'));
                                    }
                                }),
                            Select::make('present_country_id')
                                ->label('Country')
                                ->options(Country::pluck('country_name', 'id'))
                                ->default(167)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('present_region_id', null);
                                    $set('present_province_id', null);
                                    $set('present_municipality_id', null);
                                    $set('present_barangay_id', null);
                                }),
                            Select::make('present_region_id')
                                ->label('Region')
                                ->options(fn (Get $get): Collection => Region::query()
                                    ->pluck('region_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->requiredIf('present_country_id', 167)
                                ->afterStateUpdated(function (Set $set) {
                                    $set('present_province_id', null);
                                    $set('present_municipality_id', null);
                                    $set('present_barangay_id', null);
                                }),
                            Select::make('present_province_id')
                                ->label('Province')
                                ->options(fn (Get $get): Collection => Province::query()
                                    ->where('region_id', $get('present_region_id'))
                                    ->pluck('province_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->disabled(fn (Get $get) => ! filled($get('present_region_id')))
                                ->afterStateUpdated(function (Set $set) {
                                    $set('present_municipality_id', null);
                                    $set('present_barangay_id', null);
                                }),
                            Select::make('present_municipality_id')
                                ->label('City/Municipality')
                                ->options(fn (Get $get): Collection => Municipality::query()
                                    ->where('province_id', $get('present_province_id'))
                                    ->pluck('municipality_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->disabled(fn (Get $get) => ! filled($get('present_province_id')))
                                ->afterStateUpdated(function (Set $set) {
                                    $set('present_barangay_id', null);
                                }),
                            Select::make('present_barangay_id')
                                ->label('Barangay')
                                ->options(fn (Get $get): Collection => Barangay::query()
                                    ->where('municipality_id', $get('present_municipality_id'))
                                    ->pluck('barangay_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get) => ! filled($get('present_municipality_id'))),
                            Textarea::make('present_address')
                                ->label('Address Details')
                                ->placeholder('House/Unit Number, Street Name, Subdivision')
                                ->columnSpanFull(),
                        ])
                        ->columns([
                            'sm' => 1,
                            'md' => 2,
                            'lg' => 3,
                        ]),
                    Step::make('Educational Records')
                        ->schema([
                            Repeater::make('educations')
                                ->label('Educational Attainment')
                               // ->relationship()
                                ->schema([
                                    Select::make('degree_type')
                                        ->label('Degree Type')
                                        ->options([
                                            'Bachelor' => 'Bachelor',
                                            'Master' => 'Master',
                                            'Doctorate' => 'Doctorate',
                                        ])
                                        ->required()
                                        ->live(),
                                    Select::make('degree_name')
                                        ->label('Program Name')
                                        ->options(function (Get $get) {
                                            $degreetype = $get('degree_type');
                                            if ($degreetype === 'Bachelor') {
                                                return BachelorDegree::query()->pluck('bachelor_name', 'bachelor_name');
                                            } elseif ($degreetype === 'Master') {
                                                return MasteralDegree::query()->pluck('masteral_name', 'masteral_name');
                                            } elseif ($degreetype === 'Doctorate') {
                                                return DoctorateDegree::query()->pluck('doctorate_name', 'doctorate_name');
                                            }
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    Select::make('school_id')
                                        ->label('School')
                                        ->relationship('school', 'school_name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    DatePicker::make('start_date')
                                        ->label('Start Date')
                                        ->required()
                                        ->live(),
                                    DatePicker::make('end_date')
                                        ->label('End Date')
                                        ->required()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            if ($get('start_date') && $get('end_date')) {
                                                $diff = Carbon::parse($get('start_date'))->diff(Carbon::parse($get('end_date')));
                                                $parts = [];
                                                if ($diff->y > 0) {
                                                    $parts[] = $diff->y.' '.Str::plural('year', $diff->y);
                                                }
                                                if ($diff->m > 0) {
                                                    $parts[] = $diff->m.' '.Str::plural('month', $diff->m);
                                                }
                                                $set('duration_of_course', implode(', ', $parts) ?: 'Same day');
                                            }
                                        })
                                        ->live(),
                                    TextInput::make('duration_of_course')
                                        ->label('Duration')
                                        ->disabled()
                                        ->dehydrated(),
                                    TextInput::make('final_grade')
                                        ->label('Final Grade'),
                                    FileUpload::make('educational_attachment')
                                        ->label('Diploma/Transcript')
                                        ->multiple()
                                        ->directory('documents/educational')
                                        ->acceptedFileTypes(['image/*', 'application/pdf'])
                                        ->columnSpanFull(),

                                ])->columns(2)
                                ->columnSpanFull(),
                        ])
                        ->columns([
                            'sm' => 1,
                            'md' => 2,
                            'lg' => 3,
                        ]),
                    Step::make('Certifications & Licenses')
                        ->schema([
                            Repeater::make('certifications')
                                ->label('Certifications/Licenses')
                                ->relationship()
                                ->schema([
                                    TextInput::make('institution')
                                        ->label('Institution')
                                        ->required()
                                        ->columnSpanFull(),
                                    TextInput::make('license')
                                        ->label('Certification Name'),
                                    TextInput::make('license_number')
                                        ->label('License No'),
                                    DatePicker::make('date_issued')
                                        ->label('Date Issued'),
                                    DatePicker::make('date_expiry')
                                        ->label('Date Expiry'),
                                    FileUpload::make('certificate_file')
                                        ->label('Certificate')
                                        ->directory('documents/certifications')
                                        ->acceptedFileTypes(['image/*', 'application/pdf'])
                                        ->columnSpanFull(),
                                ])->columns(2)
                                ->columnSpanFull(),
                        ])
                        ->columns([
                            'sm' => 1,
                            'md' => 2,
                            'lg' => 3,
                        ]),

                ])
                    ->skippable()
                    ->columnSpan([
                        'sm' => 1,
                        'lg' => 2,
                    ]),
            ]);
    }
}
