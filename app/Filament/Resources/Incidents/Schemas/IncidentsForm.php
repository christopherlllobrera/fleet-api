<?php

namespace App\Filament\Resources\Incidents\Schemas;

use App\Models\Dispatch;
use App\Models\Vehicle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IncidentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Incident Information')
                    ->description('This section contains information about the incident.')
                    ->icon('heroicon-o-phone')
                    ->columnSpan(['lg' => 2])
                    ->columns(2)
                    ->schema([
                        Select::make('company_id')
                            ->label('Company No.')
                            ->relationship('company', 'name'),
                        // auto generate the reference no.
                        TextInput::make('reference_no')
                            ->label('Reference No.'),
                        Select::make('dispatch_id')
                            ->options(Dispatch::all()->pluck('ticket_no', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->label('Dispatch No.')
                            ->required(),
                        Select::make('vehicle_id')
                            ->options(Vehicle::all()->pluck('plate_no', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Vehicle Plate No.'),
                        TextInput::make('reported_by'),
                        DatePicker::make('reported_at'),
                        TextInput::make('location')
                            ->required(),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Incident Classification')
                    ->description('This section contains information about the classification of the incident.')
                    ->icon('heroicon-o-information-circle')
                    ->columnSpan(['lg' => 1])
                    ->columns(1)
                    ->schema([
                        Select::make('incident_severity')
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
                            ->options([
                                'Open' => 'Open',
                                'In Progress' => 'In Progress',
                                'Resolved' => 'Resolved',
                                'Closed' => 'Closed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Select::make('type')
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
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
