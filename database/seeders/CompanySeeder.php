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
        if (DB::table('companies')->where('id', 1)->doesntExist()) {
            DB::table('companies')->insert([
                'id' => 1,
                'name' => 'MIESCOR',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('companies')->where('id', 2)->doesntExist()) {
            DB::table('companies')->insert([
                'id' => 2,
                'name' => 'Meralco',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
