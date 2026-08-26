<?php

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Concerns\CanBeReadOnly;
use Filament\Forms\Components\Field;

class LocationPicker extends Field
{
    use CanBeReadOnly;

    protected string $view = 'filament.forms.components.location-picker';

    protected float|Closure|null $defaultLat = 14.5898967;

    protected float|Closure|null $defaultLng = 121.0639172;

    protected int|Closure|null $defaultZoom = 15;

    protected int|Closure|null $height = 400;

    protected int|Closure|null $defaultRadius = 500;

    protected string|Closure|null $latField = 'lat';

    protected string|Closure|null $lngField = 'lng';

    protected string|Closure|null $radiusField = null;

    protected string|Closure|null $addressField = null;

    protected string|Closure|null $shortAddressField = null;

    protected string|Closure|null $streetField = null;

    protected string|Closure|null $streetNumberField = null;

    protected string|Closure|null $provinceField = null;

    protected string|Closure|null $cityField = null;

    protected string|Closure|null $districtField = null;

    protected string|Closure|null $villageField = null;

    protected string|Closure|null $postalCodeField = null;

    protected string|Closure|null $countryField = null;

    protected bool|Closure $draggable = true;

    protected bool|Closure $searchable = true;

    protected string|Closure|null $tileUrl = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png';

    protected string|Closure|null $tileUrlDark = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png';

    protected string|Closure|null $tileAttribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://carto.com/attributions">CARTO</a>';

    protected string|Closure|null $nominatimUrl = 'https://nominatim.openstreetmap.org';

    protected string|Closure|null $countryCodes = 'ph';

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (LocationPicker $component, $record): void {
            if ($record) {
                $latField = $component->getLatField();
                $lngField = $component->getLngField();
                $radiusField = $component->getRadiusField();
                $addressField = $component->getAddressField();

                $state = [
                    'lat' => data_get($record, $latField) ?? $component->getDefaultLat(),
                    'lng' => data_get($record, $lngField) ?? $component->getDefaultLng(),
                ];

                if ($radiusField && ($radius = data_get($record, $radiusField))) {
                    $state['radius'] = $radius;
                }

                if ($addressField && ($address = data_get($record, $addressField))) {
                    $state['address'] = $address;
                }

                $component->state($state);
            }
        });

        $this->dehydrateStateUsing(function ($state) {
            return $state;
        });
    }

    public function defaultLocation(float|Closure $lat, float|Closure $lng): static
    {
        $this->defaultLat = $lat;
        $this->defaultLng = $lng;

        return $this;
    }

    public function defaultZoom(int|Closure $zoom): static
    {
        $this->defaultZoom = $zoom;

        return $this;
    }

    public function height(int|Closure $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function defaultRadius(int|Closure|null $radius): static
    {
        $this->defaultRadius = $radius;

        return $this;
    }

    public function latField(string|Closure|null $field): static
    {
        $this->latField = $field;

        return $this;
    }

    public function lngField(string|Closure|null $field): static
    {
        $this->lngField = $field;

        return $this;
    }

    public function radiusField(string|Closure|null $field): static
    {
        $this->radiusField = $field;

        return $this;
    }

    public function addressField(string|Closure|null $field): static
    {
        $this->addressField = $field;

        return $this;
    }

    public function shortAddressField(string|Closure|null $field): static
    {
        $this->shortAddressField = $field;

        return $this;
    }

    public function streetField(string|Closure|null $field): static
    {
        $this->streetField = $field;

        return $this;
    }

    public function streetNumberField(string|Closure|null $field): static
    {
        $this->streetNumberField = $field;

        return $this;
    }

    public function provinceField(string|Closure|null $field): static
    {
        $this->provinceField = $field;

        return $this;
    }

    public function cityField(string|Closure|null $field): static
    {
        $this->cityField = $field;

        return $this;
    }

    public function districtField(string|Closure|null $field): static
    {
        $this->districtField = $field;

        return $this;
    }

    public function villageField(string|Closure|null $field): static
    {
        $this->villageField = $field;

        return $this;
    }

    public function postalCodeField(string|Closure|null $field): static
    {
        $this->postalCodeField = $field;

        return $this;
    }

    public function countryField(string|Closure|null $field): static
    {
        $this->countryField = $field;

        return $this;
    }

    public function draggable(bool|Closure $draggable = true): static
    {
        $this->draggable = $draggable;

        return $this;
    }

    public function searchable(bool|Closure $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function tileUrl(string|Closure|null $tileUrl): static
    {
        $this->tileUrl = $tileUrl;

        return $this;
    }

    public function tileUrlDark(string|Closure|null $tileUrlDark): static
    {
        $this->tileUrlDark = $tileUrlDark;

        return $this;
    }

    public function tileAttribution(string|Closure|null $tileAttribution): static
    {
        $this->tileAttribution = $tileAttribution;

        return $this;
    }

    public function nominatimUrl(string|Closure|null $nominatimUrl): static
    {
        $this->nominatimUrl = $nominatimUrl;

        return $this;
    }

    public function countryCodes(string|Closure|null $countryCodes): static
    {
        $this->countryCodes = $countryCodes;

        return $this;
    }

    public function getDefaultLat(): float
    {
        return (float) ($this->evaluate($this->defaultLat) ?? 14.5898967);
    }

    public function getDefaultLng(): float
    {
        return (float) ($this->evaluate($this->defaultLng) ?? 121.0639172);
    }

    public function getDefaultZoom(): int
    {
        return (int) ($this->evaluate($this->defaultZoom) ?? 15);
    }

    public function getHeight(): int
    {
        return (int) ($this->evaluate($this->height) ?? 400);
    }

    public function getDefaultRadius(): ?int
    {
        $val = $this->evaluate($this->defaultRadius);

        return $val !== null ? (int) $val : 500;
    }

    public function getLatField(): ?string
    {
        return $this->evaluate($this->latField);
    }

    public function getLngField(): ?string
    {
        return $this->evaluate($this->lngField);
    }

    public function getRadiusField(): ?string
    {
        return $this->evaluate($this->radiusField);
    }

    public function getAddressField(): ?string
    {
        return $this->evaluate($this->addressField);
    }

    public function getShortAddressField(): ?string
    {
        return $this->evaluate($this->shortAddressField);
    }

    public function getStreetField(): ?string
    {
        return $this->evaluate($this->streetField);
    }

    public function getStreetNumberField(): ?string
    {
        return $this->evaluate($this->streetNumberField);
    }

    public function getProvinceField(): ?string
    {
        return $this->evaluate($this->provinceField);
    }

    public function getCityField(): ?string
    {
        return $this->evaluate($this->cityField);
    }

    public function getDistrictField(): ?string
    {
        return $this->evaluate($this->districtField);
    }

    public function getVillageField(): ?string
    {
        return $this->evaluate($this->villageField);
    }

    public function getPostalCodeField(): ?string
    {
        return $this->evaluate($this->postalCodeField);
    }

    public function getCountryField(): ?string
    {
        return $this->evaluate($this->countryField);
    }

    public function isDraggable(): bool
    {
        return (bool) $this->evaluate($this->draggable);
    }

    public function isSearchable(): bool
    {
        return (bool) $this->evaluate($this->searchable);
    }

    public function getTileUrl(): string
    {
        return $this->evaluate($this->tileUrl) ?? 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png';
    }

    public function getTileUrlDark(): ?string
    {
        return $this->evaluate($this->tileUrlDark) ?? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png';
    }

    public function getTileAttribution(): string
    {
        return $this->evaluate($this->tileAttribution) ?? '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://carto.com/attributions">CARTO</a>';
    }

    public function getNominatimUrl(): string
    {
        return rtrim($this->evaluate($this->nominatimUrl) ?? 'https://nominatim.openstreetmap.org', '/');
    }

    public function getCountryCodes(): ?string
    {
        return $this->evaluate($this->countryCodes) ?? 'ph';
    }
}
