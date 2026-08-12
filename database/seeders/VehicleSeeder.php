<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * CSV Column Mapping (vehicle_2026_fixed.csv):
     * [0]  = No. (row number — not stored)
     * [1]  = Charge Account
     * [2]  = Ownership
     * [3]  = plate_no
     * [4]  = device_sn
     * [5]  = business_unit_id (Business Unit name)
     * [6]  = model
     * [7]  = year
     * [8]  = maker_id (Maker name)
     * [9]  = vehicle_category_id (Category name)
     * [10] = vehicle_group_id (Group name)
     * [11] = status
     * [12] = vehicle_power_type_id (Power Type name)
     */
    public function run(): void
    {
        DB::disableQueryLog();

        // Ensure Charge Accounts and Business Units are seeded first
        $this->call(ChargeAccountSeeder::class);

        $rows = $this->loadCsv();

        $this->seedVehiclePowerTypes($rows);
        $this->seedVehicleCategories($rows);
        $this->seedVehicleGroups($rows);
        $this->seedMakers($rows);
        $this->seedVehicles($rows);
    }

    /**
     * Load CSV rows into an array, skipping header line.
     *
     * @return array<int, array<int, string>>
     */
    private function loadCsv(): array
    {
        $rows = [];
        $filePath = database_path('seeders/CSV/vehicle_2026_fixed.csv');

        if (! file_exists($filePath)) {
            return [];
        }

        $file = fopen($filePath, 'r');
        fgetcsv($file); // skip header line

        while (($line = fgetcsv($file, 4096)) !== false) {
            if (empty(array_filter($line))) {
                continue;
            }
            $rows[] = array_map(function ($val) {
                $val = trim($val);

                return mb_check_encoding($val, 'UTF-8') ? $val : mb_convert_encoding($val, 'UTF-8', 'Windows-1252');
            }, $line);
        }

        fclose($file);

        return $rows;
    }

    /**
     * Seed vehicle_power_types from unique values in CSV column [12].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehiclePowerTypes(array $rows): void
    {
        $powerTypes = collect($rows)
            ->pluck(12)
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
     * Seed vehicle_categories from unique values in CSV column [9].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehicleCategories(array $rows): void
    {
        $categories = collect($rows)
            ->pluck(9)
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
     * Seed vehicle_groups from unique values in CSV column [10].
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehicleGroups(array $rows): void
    {
        $groups = collect($rows)
            ->pluck(10)
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
     * Normalize dirty maker names.
     */
    private function normalizeMaker(string $raw): string
    {
        $name = strtoupper(trim($raw));
        $map = [
            'TOYOYA' => 'TOYOTA',
            'HILUX' => 'TOYOTA',
            'SUZUKIAPVCARRIERUV' => 'SUZUKI',
            'HONDAWAVE' => 'HONDA',
            'KOMATSUFORKLIFT' => 'KOMATSU',
            'SINO' => 'SINOTRUK',
        ];

        return $map[$name] ?? $name;
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
            'BYD' => 'China',
            'SINOTRUK' => 'China',
        ];

        $makers = collect($rows)
            ->pluck(8)
            ->map(fn (string $value) => $this->normalizeMaker($value))
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
     * Seed vehicles from the CSV.
     *
     * @param  array<int, array<int, string>>  $rows
     */
    private function seedVehicles(array $rows): void
    {
        // Build lookup maps (case-insensitive where appropriate)
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

        $businessUnits = DB::table('business_units')->pluck('id', 'name');
        $chargeAccounts = DB::table('charge_accounts')->pluck('id', 'name');

        $inserts = [];

        foreach ($rows as $row) {
            $caName = trim($row[1] ?? '');
            $buName = trim($row[5] ?? '');

            $chargeAccountId = $chargeAccounts[$caName] ?? null;
            $buId = $businessUnits[$buName] ?? null;

            $makerKey = $this->normalizeMaker(trim($row[8] ?? ''));
            $categoryKey = strtoupper(trim($row[9] ?? ''));
            $groupKey = strtoupper(trim($row[10] ?? ''));
            $powerTypeKey = strtoupper(trim($row[12] ?? ''));

            $year = trim($row[7] ?? '');
            $year = ($year === '' || $year === '0') ? null : $year;

            $status = trim($row[11] ?? '');
            $status = $status === '' ? 'Unknown' : $status;

            $ownership = trim($row[2] ?? '');
            $ownership = $ownership === '' ? null : $ownership;

            $deviceSn = trim($row[4] ?? '');
            $deviceSn = $deviceSn === '' ? null : $deviceSn;

            $inserts[] = [
                'charge_account_id' => $chargeAccountId,
                'company_id' => null,
                'business_unit_id' => $buId,
                'ownership' => $ownership,
                'plate_no' => trim($row[3] ?? ''),
                'device_sn' => $deviceSn,
                'init_odo' => null,
                'maker_id' => $makers[$makerKey] ?? null,
                'model' => trim($row[6] ?? ''),
                'year' => $year,
                'status' => $status,
                'vehicle_category_id' => $categories[$categoryKey] ?? null,
                'vehicle_power_type_id' => $powerTypes[$powerTypeKey] ?? null,
                'vehicle_group_id' => $groups[$groupKey] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in chunks to avoid query parameter limits
        foreach (array_chunk($inserts, 500) as $chunk) {
            DB::table('vehicles')->insert($chunk);
        }
    }
}
