<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class MasteralDegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        LazyCollection::make(function () {
            $file = fopen(database_path('seeders/CSV/Academe/Masteral.csv'), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }
            fclose($file);
        })
            ->chunk(1000)
            ->each(function ($lines) {
                DB::table('masteral_degrees')->insert($lines->map(function ($line) {
                    return [
                        'masteral_name' => $line[0],
                        'created_at' => $line[1] ?? now(),
                        'updated_at' => $line[2] ?? now(),
                    ];
                })->toArray());
            });
    }
}
