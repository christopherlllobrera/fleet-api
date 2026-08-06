<?php

namespace App\Filament\Resources\Dispatches\Pages;

use App\Models\Dispatch;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Str;

class DispatchExporter extends Exporter
{
    protected static ?string $model = Dispatch::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('ticket_no'),
            ExportColumn::make('request_item'),
            ExportColumn::make('passenger_count'),
            ExportColumn::make('vehicle.plate_no')
                ->label('Plate No.'),
            ExportColumn::make('driver.employee.full_name')
                ->label('Driver'),
            ExportColumn::make('requesting_office.office_name')
                ->label('Office'),
            ExportColumn::make('from_location'),
            ExportColumn::make('from_lat'),
            ExportColumn::make('from_lng'),
            ExportColumn::make('to_location'),
            ExportColumn::make('to_lat'),
            ExportColumn::make('to_lng'),
            ExportColumn::make('purpose'),
            ExportColumn::make('priority_level'),
            ExportColumn::make('departure_time'),
            ExportColumn::make('en_route_time'),
            ExportColumn::make('complete_time'),
            ExportColumn::make('cancel_time'),
            ExportColumn::make('reason'),
            ExportColumn::make('status'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your dispatch export has completed and ' . Str::of('row')->counted($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Str::of('row')->counted($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
