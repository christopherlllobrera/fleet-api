<?php

namespace App\Filament\Resources\Dispatches\Pages;

use App\Filament\Resources\Dispatches\DispatchResource;
use App\Models\Dispatch;
use Carbon\Carbon;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ViewDispatch extends Page
{
    use InteractsWithRecord;

    protected static string $resource = DispatchResource::class;

    protected string $view = 'filament.pages.dispatch-info';

    protected static string $routePath = 'dispatch-view';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->load(['driver.employee', 'vehicle.maker', 'vehicle.vehicleCategory', 'vehicle.vehiclePowerType', 'vehicle.vehicleGroup', 'requesting_office']);
    }

    // ──────────────────────────────────────────────
    // Route Information Helpers
    // ──────────────────────────────────────────────

    public function getFromLocation(): string
    {
        return $this->record->from_location ?: '—';
    }

    public function getToLocation(): string
    {
        return $this->record->to_location ?: '—';
    }

    public function getFromLocationRaw(): string
    {
        return $this->record->from_location ?: '';
    }

    public function getToLocationRaw(): string
    {
        return $this->record->to_location ?: '';
    }

    public function getDepartureTime(): string
    {
        return $this->record->departure_time
            ? Carbon::parse($this->record->departure_time)->format('M d, Y h:i A')
            : '—';
    }

    public function getArrivalTime(): string
    {
        return $this->record->arrival_time
            ? Carbon::parse($this->record->arrival_time)->format('M d, Y h:i A')
            : '';
    }

    public function getStatus(): string
    {
        return $this->record->status ?? 'Pending';
    }

    public function getStatusColor(): string
    {
        return match ($this->record->status) {
            'Pending' => 'gray',
            'Assigned' => 'info',
            'Unassigned' => 'primary',
            'Unserved' => 'info',
            'Cancelled' => 'danger',
            'Completed' => 'success',
            default => 'gray',
        };
    }

    public function getEnrouteTime(): string
    {
        return $this->record->en_route_time
            ? Carbon::parse($this->record->en_route_time)->format('M d, Y h:i A')
            : '—';
    }

    public function getCompletedTime(): string
    {
        return $this->record->complete_time
            ? Carbon::parse($this->record->complete_time)->format('M d, Y h:i A')
            : '—';
    }

    public function getTripDuration(): string
    {
        if (! $this->record->departure_time || ! $this->record->complete_time) {
            return '';
        }

        $start = Carbon::parse($this->record->departure_time);
        $end = Carbon::parse($this->record->complete_time);
        $minutes = $start->diffInMinutes($end);

        if ($minutes >= 60) {
            $hours = intdiv($minutes, 60);
            $mins = $minutes % 60;

            return $hours.' h '.$mins.' min';
        }

        return $minutes.' min';
    }

    public function getHaversineDistance(): string
    {
        if (
            ! $this->record->from_lat
            || ! $this->record->from_lng
            || ! $this->record->to_lat
            || ! $this->record->to_lng
        ) {
            return 'Calculating...';
        }

        $earthRadius = 6371;
        $latFrom = deg2rad((float) $this->record->from_lat);
        $lngFrom = deg2rad((float) $this->record->from_lng);
        $latTo = deg2rad((float) $this->record->to_lat);
        $lngTo = deg2rad((float) $this->record->to_lng);
        $latDiff = $latTo - $latFrom;
        $lngDiff = $lngTo - $lngFrom;
        $a = sin($latDiff / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lngDiff / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return number_format($distance, 2).' km';
    }

    public function getFromCoordinates(): ?array
    {
        if ($this->record->from_lat && $this->record->from_lng) {
            return [(float) $this->record->from_lat, (float) $this->record->from_lng];
        }

        return null;
    }

    public function getToCoordinates(): ?array
    {
        if ($this->record->to_lat && $this->record->to_lng) {
            return [(float) $this->record->to_lat, (float) $this->record->to_lng];
        }

        return null;
    }

    // ──────────────────────────────────────────────
    // Dispatch Information Helpers
    // ──────────────────────────────────────────────
    public function getPurpose(): string
    {
        return $this->record->purpose ?? '—';
    }

    public function getPriorityLevel(): string
    {
        return $this->record->priority_level ?? '—';
    }

    public function getPriorityLevelColor(): string
    {
        return match ($this->record->priority_level) {
            'High' => 'danger',
            'Medium' => 'warning',
            'Low' => 'success',
            default => 'gray',
        };
    }

    public function getTicketNo(): string
    {
        return $this->record->ticket_no ?? '—';
    }

    public function getRequestItem(): string
    {
        return $this->record->request_item ?? '—';
    }

    public function getPassengerCount(): string
    {
        return $this->record->passenger_count ?? '—';
    }

    public function getRequestingOffice(): string
    {
        return $this->record->requesting_office?->office_name ?? '—';
    }

    // ──────────────────────────────────────────────
    // Trip Information Helpers
    // ──────────────────────────────────────────────

    public function getDriverName(): string
    {
        return $this->record->driver?->full_name ?? '—';
    }

    public function getContactNumber(): string
    {
        return $this->record->driver?->contact_no ?? '—';
    }

    public function getPersonnelNo(): string
    {
        return $this->record->driver?->personnel_no ?? '—';
    }

    public function getPlateNumber(): string
    {
        return $this->record->vehicle?->plate_no ?? '—';
    }

    public function getVehicleMaker(): string
    {
        return $this->record->vehicle?->maker?->name ?? '—';
    }

    public function getVehicleModel(): string
    {
        return $this->record->vehicle?->model ?? '—';
    }

    public function getVehicleYear(): string
    {
        return $this->record->vehicle?->year ?? '—';
    }

    public function getVehiclePowerType(): string
    {
        return $this->record->vehicle?->vehiclePowerType?->name ?? '—';
    }

    public function getVehicleCategory(): string
    {
        return $this->record->vehicle?->vehicleCategory?->name ?? '—';
    }

    public function getVehicleGroup(): string
    {
        return $this->record->vehicle?->vehicleGroup?->name ?? '—';
    }

    // ──────────────────────────────────────────────
    // Cancellation Helpers
    // ──────────────────────────────────────────────

    public function getCancellationRecord(): ?string
    {
        return $this->record->reason ?? null;
    }

    // ──────────────────────────────────────────────
    // Completed Trip Helpers
    // ──────────────────────────────────────────────

    public function getEstimatedDistance(): ?float
    {
        if (
            $this->record->from_lat
            && $this->record->from_lng
            && $this->record->to_lat
            && $this->record->to_lng
        ) {
            $earthRadius = 6371;
            $latFrom = deg2rad((float) $this->record->from_lat);
            $lngFrom = deg2rad((float) $this->record->from_lng);
            $latTo = deg2rad((float) $this->record->to_lat);
            $lngTo = deg2rad((float) $this->record->to_lng);
            $latDiff = $latTo - $latFrom;
            $lngDiff = $lngTo - $lngFrom;
            $a = sin($latDiff / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lngDiff / 2) ** 2;
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            return $earthRadius * $c;
        }

        return null;
    }

    public function getFuelEfficiency(): ?float
    {
        return $this->record->vehicle?->fuel_efficiency ?? null;
    }

    // ──────────────────────────────────────────────
    // Toll Points Helper
    // ──────────────────────────────────────────────

    public function getTollPoints(): array
    {
        return $this->record->toll_points ?? [];
    }
}
