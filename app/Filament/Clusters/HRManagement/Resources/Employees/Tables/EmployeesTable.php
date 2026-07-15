<?php

namespace App\Filament\Clusters\HRManagement\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_no')
                    ->label('Employee No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.department_description')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position.position_description')
                    ->label('Position')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name'),
                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'department_description'),
                Filter::make('is_active')
                    ->toggle()
                    ->label('Active Only'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
