<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Employee;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driverEmployees = Employee::whereHas('position', function ($query) {
            $query->where('position_description', 'Driver');
        })->get();

        $faker = Factory::create();

        foreach ($driverEmployees as $employee) {
            Driver::create([
                'employee_id' => $employee->id,
                'license_no' => $faker->bothify('DL-202#-###-PH'),
                'license_expiry' => now()->addYears(3)->format('Y-m-d'),
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => now()->addYear()->format('Y-m-d'),
                'country_id' => '167',
            ]);
        }
    }
}
