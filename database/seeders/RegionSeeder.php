<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function () {
            $file = fopen(database_path('seeders/CSV/Location/table_region.csv'), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }
            fclose($file);
        })
            ->chunk(1000)
            ->each(function ($lines) {
                DB::table('regions')->insert($lines->map(function ($line) {
                    return [
                        'id' => $line[0],
                        'region_name' => $line[1],
                        'region_description' => $line[2],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray());
            });
    }
}
