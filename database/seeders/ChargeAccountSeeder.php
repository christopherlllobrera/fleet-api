<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChargeAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * CSV Column Mapping (vehicle_2026.csv):
     * [1] = Charge Account (e.g. 26-0104)
     * [3] = Business Unit Name (e.g. MIESCOR - PM of Company Vehicle)
     */
    public function run(): void
    {
        DB::disableQueryLog();

        $path = database_path('seeders/CSV/vehicle_2026.csv');
        if (! file_exists($path)) {
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // skip header

        $pairs = [];
        while (($row = fgetcsv($file, 4096)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }
            $row = array_map(function ($val) {
                $val = trim($val);

                return mb_check_encoding($val, 'UTF-8') ? $val : mb_convert_encoding($val, 'UTF-8', 'Windows-1252');
            }, $row);
            $chargeAccount = $row[1] ?? '';
            $buName = $row[3] ?? '';

            if ($chargeAccount !== '' && $buName !== '') {
                $key = $chargeAccount.'|'.$buName;
                $pairs[$key] = [
                    'charge_account' => $chargeAccount,
                    'business_unit' => $buName,
                ];
            }
        }
        fclose($file);

        // 1. Ensure business units exist (with company_id = null)
        $existingBUs = DB::table('business_units')->pluck('id', 'name');

        foreach ($pairs as $item) {
            $buName = $item['business_unit'];
            if (! isset($existingBUs[$buName])) {
                $id = DB::table('business_units')->insertGetId([
                    'company_id' => null,
                    'name' => $buName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $existingBUs[$buName] = $id;
            }
        }

        // 2. Ensure charge accounts exist
        $existingChargeAccounts = DB::table('charge_accounts')->pluck('id', 'name');

        foreach ($pairs as $item) {
            $caName = $item['charge_account'];
            $buName = $item['business_unit'];
            $buId = $existingBUs[$buName] ?? null;

            if (! isset($existingChargeAccounts[$caName])) {
                $id = DB::table('charge_accounts')->insertGetId([
                    'name' => $caName,
                    'business_unit_id' => $buId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $existingChargeAccounts[$caName] = $id;
            }
        }
    }
}
