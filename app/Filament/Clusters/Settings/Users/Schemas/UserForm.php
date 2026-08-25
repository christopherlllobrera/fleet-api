<?php

namespace App\Filament\Clusters\Settings\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->description('Manage user information and settings.')
                    ->collapsible()
                    ->schema([
                        TextInput::make('employee_no')
                            ->label('Employee No.')
                            ->inlineLabel()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->validationMessages([
                                'unique' => 'The employee number has already been taken.',
                                'required' => 'The employee number field is required.',
                            ]),
                        TextInput::make('name')
                            ->required()
                            ->inlineLabel(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->inlineLabel()
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->inlineLabel()
                            ->revealable(),
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->inlineLabel()
                            ->validationMessages([
                                'required' => 'The role field is required.',
                            ])
                            ->columnStart(2),
                        FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->inlineLabel()
                            ->visibleOn('edit')
                            ->default(true),
                    ])
                    ->columns([
                        'sm' => 1,
                        'lg' => 2,
                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'lg' => 2,
                    ]),
            ]);
    }
}
