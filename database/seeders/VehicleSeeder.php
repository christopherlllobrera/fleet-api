<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * CSV Column Mapping:
     * [0] = row number
     * [1] = code (N/A or TV-CR-xxx, etc.) — not stored
     * [2] = plate_no
     * [3] = model description (e.g., TOYOTA HILUX)
     * [4] = vehicle group (LIGHT, MEDIUM, HEAVY)
     * [5] = vehicle category (PICK UP, VAN, SEDAN, etc.)
     * [6] = year
     * [7] = power type (DIESEL, GASOLINE, LPG, GAS)
     * [8] = maker name (TOYOTA, MITSUBISHI, etc.)
     * [9] = business unit name
     * [10] = project code — not stored
     * [11] = project name — not stored
     * [12] = status (Leasing, Charge to MLI, etc.)
     * [13] = company type (Internal, External)
     * [14] = created_at
     * [15] = updated_at
     */
    public function run(): void
    {
        DB::disableQueryLog();

        $rows = $this->loadCsv();

        $this->seedVehiclePowerTypes($rows);
        $this->seedVehicleCategories($rows);
        $this->seedVehicleGroups($rows);
        $this->seedMakers($rows);
        $this->seedCompaniesAndBusinessUnits($rows);
        $this->seedVehicles($rows);
    }

    /**
     * Load CSV rows into an array.
     *
     * @return array<int, array<int, string>>
     */
    private function loadCsv(): array
    {
        $rows = [];
        $file = fopen(database_path('seeders/CSV/vehicles_consolidated.csv'), 'r');

        while (($line = fgetcsv($file, 4096)) !== false) {
            if (empty(array_filter($line))) {
                continue;
            }
            // Trim whitespace from all fields
            $rows[] = array_map('trim', $line);
        }

        fclose($file);

        return $rows;
    }

    /**
     * Seed vehicle_power_types from unique values in CSV column [7].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehiclePowerTypes(array $rows): void
    {
        $powerTypes = collect($rows)
            ->pluck(7)
            ->map(fn (string $value) => strtoupper(trim($value)))
            ->filter()
            ->unique()
            ->values();

        $existing = DB::table('vehicle_power_types')->pluck('name')->map(fn ($n) => strtoupper($n));

        $inserts = $powerTypes
            ->diff($existing)
            ->map(fn (string $name) => [
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->values()
            ->toArray();

        if (! empty($inserts)) {
            DB::table('vehicle_power_types')->insert($inserts);
        }
    }

    /**
     * Seed vehicle_categories from unique values in CSV column [5].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehicleCategories(array $rows): void
    {
        $categories = collect($rows)
            ->pluck(5)
            ->map(fn (string $value) => strtoupper(trim($value)))
            ->filter()
            ->unique()
            ->values();

        $existing = DB::table('vehicle_categories')->pluck('name')->map(fn ($n) => strtoupper($n));

        $inserts = $categories
            ->diff($existing)
            ->map(fn (string $name) => [
                'name' => $name,
                'description' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->values()
            ->toArray();

        if (! empty($inserts)) {
            DB::table('vehicle_categories')->insert($inserts);
        }
    }

    /**
     * Seed vehicle_groups from unique values in CSV column [4].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehicleGroups(array $rows): void
    {
        $groups = collect($rows)
            ->pluck(4)
            ->map(fn (string $value) => strtoupper(trim($value)))
            ->filter()
            ->unique()
            ->values();

        $existing = DB::table('vehicle_groups')->pluck('name')->map(fn ($n) => strtoupper($n));

        $inserts = $groups
            ->diff($existing)
            ->map(fn (string $name) => [
                'name' => $name,
                'description' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->values()
            ->toArray();

        if (! empty($inserts)) {
            DB::table('vehicle_groups')->insert($inserts);
        }
    }

    /**
     * Seed makers from unique values in CSV column [8].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedMakers(array $rows): void
    {
        $makerCountryMap = [
            'TOYOTA' => 'Japan',
            'MITSUBISHI' => 'Japan',
            'HONDA' => 'Japan',
            'SUZUKI' => 'Japan',
            'NISSAN' => 'Japan',
            'ISUZU' => 'Japan',
            'HINO' => 'Japan',
            'FUSO' => 'Japan',
            'KOMATSU' => 'Japan',
            'HYUNDAI' => 'South Korea',
            'FORD' => 'United States',
            'CHEVROLET' => 'United States',
            'CATERPILLAR' => 'United States',
            'MAHINDRA' => 'India',
            'TATA' => 'India',
        ];

        $makers = collect($rows)
            ->pluck(8)
            ->map(fn (string $value) => strtoupper(trim($value)))
            ->filter()
            ->unique()
            ->values();

        $existing = DB::table('makers')->pluck('name')->map(fn ($n) => strtoupper($n));

        $inserts = $makers
            ->diff($existing)
            ->map(fn (string $name) => [
                'name' => $name,
                'country' => $makerCountryMap[$name] ?? 'Unknown',
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->values()
            ->toArray();

        if (! empty($inserts)) {
            DB::table('makers')->insert($inserts);
        }
    }

    /**
     * Seed companies from CSV column [13] and business_units from column [9].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedCompaniesAndBusinessUnits(array $rows): void
    {
        // Seed companies from column [13] (Internal / External)
        $companyNames = collect($rows)
            ->pluck(13)
            ->map(fn (string $value) => trim($value))
            ->filter()
            ->unique()
            ->values();

        $existingCompanies = DB::table('companies')->pluck('name', 'id');

        foreach ($companyNames as $companyName) {
            if (! $existingCompanies->contains($companyName)) {
                DB::table('companies')->insert([
                    'name' => $companyName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Refresh company lookup
        $companyLookup = DB::table('companies')->pluck('id', 'name');

        // Seed business units — each unique combo of (company, business_unit_name)
        $businessUnits = collect($rows)
            ->map(fn (array $row) => [
                'company' => trim($row[13]),
                'business_unit' => trim($row[9]),
            ])
            ->filter(fn (array $item) => ! empty($item['company']) && ! empty($item['business_unit']))
            ->unique(fn (array $item) => $item['company'].'|'.$item['business_unit'])
            ->values();

        $existingBUs = DB::table('business_units')
            ->get(['company_id', 'name'])
            ->map(fn ($bu) => $bu->company_id.'|'.$bu->name);

        $inserts = [];
        foreach ($businessUnits as $bu) {
            $companyId = $companyLookup[$bu['company']] ?? null;
            if ($companyId === null) {
                continue;
            }
            $key = $companyId.'|'.$bu['business_unit'];
            if (! $existingBUs->contains($key)) {
                $inserts[] = [
                    'company_id' => $companyId,
                    'name' => $bu['business_unit'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $existingBUs->push($key);
            }
        }

        if (! empty($inserts)) {
            foreach (array_chunk($inserts, 500) as $chunk) {
                DB::table('business_units')->insert($chunk);
            }
        }
    }

    /**
     * Seed vehicles from the CSV.
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehicles(array $rows): void
    {
        // Build lookup maps (case-insensitive)
        $powerTypes = DB::table('vehicle_power_types')
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [strtoupper($name) => $id]);

        $categories = DB::table('vehicle_categories')
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [strtoupper($name) => $id]);

        $groups = DB::table('vehicle_groups')
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [strtoupper($name) => $id]);

        $makers = DB::table('makers')
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [strtoupper($name) => $id]);

        $companies = DB::table('companies')->pluck('id', 'name');

        $businessUnits = DB::table('business_units')
            ->get(['id', 'company_id', 'name'])
            ->mapWithKeys(fn ($bu) => [$bu->company_id.'|'.$bu->name => $bu->id]);

        $inserts = [];

        foreach ($rows as $row) {
            $companyName = trim($row[13]);
            $buName = trim($row[9]);
            $companyId = $companies[$companyName] ?? null;
            $buId = $businessUnits[($companyId ?? '').'|'.$buName] ?? null;

            if ($companyId === null || $buId === null) {
                continue;
            }

            $powerTypeKey = strtoupper(trim($row[7]));
            $categoryKey = strtoupper(trim($row[5]));
            $groupKey = strtoupper(trim($row[4]));
            $makerKey = strtoupper(trim($row[8]));

            $year = trim($row[6]);
            $year = ($year === '' || $year === '0') ? null : $year;

            $status = trim($row[12]);
            $status = $status === '' ? 'Unknown' : $status;

            $inserts[] = [
                'company_id' => $companyId,
                'business_unit_id' => $buId,
                'plate_no' => trim($row[2]),
                'device_sn' => null,
                'init_odo' => null,
                'maker_id' => $makers[$makerKey] ?? null,
                'model' => trim($row[3]),
                'year' => now(),
                'status' => $status,
                'vehicle_category_id' => $categories[$categoryKey] ?? null,
                'vehicle_power_type_id' => $powerTypes[$powerTypeKey] ?? null,
                'vehicle_group_id' => $groups[$groupKey] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($inserts, 500) as $chunk) {
            DB::table('vehicles')->insert($chunk);
        }
    }
}
