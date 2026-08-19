<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->where('name', 'MIESCOR Logistics Inc.')->update(['name' => 'MIESCOR Logistic Inc.']);

        $companies = [
            'Meralco',
            'MIESCOR',
            'MIESCOR Logistic Inc.',
        ];

        foreach ($companies as $name) {
            DB::table('companies')->updateOrInsert(
                ['name' => $name],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
