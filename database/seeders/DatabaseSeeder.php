<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RegionSeeder::class,
            ProvinceSeeder::class,
            MunicipalitySeeder::class,
            BarangaySeeder::class,
            CountrySeeder::class,
            NationalitySeeder::class,
            SchoolSeeder::class,
            BachelorDegreeSeeder::class,
            MasteralDegreeSeeder::class,
            DoctorateDegreeSeeder::class,
            VehicleSeeder::class,
            EmployeeSeeder::class,
            TollRoadSeeder::class,
            TollPointSeeder::class,
            DriverSeeder::class,
            RequestingOfficeSeeder::class,
        ]);
    }
}
