<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function () {
            $file = fopen(database_path('seeders/CSV/Location/table_barangay.csv'), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }
            fclose($file);
        })
            ->chunk(1000)
            ->each(function ($lines) {
                DB::table('barangays')->insert($lines->map(function ($line) {
                    return [
                        'id' => $line[0],
                        'municipality_id' => $line[1],
                        'barangay_name' => $line[2],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray());
            });
    }
}
