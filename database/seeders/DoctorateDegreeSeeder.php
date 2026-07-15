<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class DoctorateDegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function () {
            $file = fopen(database_path('seeders/CSV/Academe/Doctorate.csv'), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }
            fclose($file);
        })
            ->chunk(1000)
            ->each(function ($lines) {
                DB::table('doctorate_degrees')->insert($lines->map(function ($line) {
                    return [
                        'id' => $line[0],
                        'doctorate_name' => $line[1],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray());
            });
    }
}
