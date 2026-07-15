<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'employee_id' => 1,
                'license_no' => 'DL-2022-001-PH',
                'license_expiry' => '2027-06-15',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2025-12-10',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 2,
                'license_no' => 'DL-2022-002-PH',
                'license_expiry' => '2026-09-20',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2025-08-15',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 3,
                'license_no' => 'DL-2021-003-PH',
                'license_expiry' => '2028-03-10',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2026-01-20',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 4,
                'license_no' => 'DL-2022-004-PH',
                'license_expiry' => '2027-11-01',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2025-09-30',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 5,
                'license_no' => 'DL-2021-005-PH',
                'license_expiry' => '2028-08-15',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2026-04-10',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 6,
                'license_no' => 'DL-2023-006-PH',
                'license_expiry' => '2028-07-10',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2025-10-20',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 7,
                'license_no' => 'DL-2022-007-PH',
                'license_expiry' => '2025-05-20',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2025-03-15',
                'country_id' => '167',
            ],
            [
                'employee_id' => 8,
                'license_no' => 'DL-2023-008-PH',
                'license_expiry' => '2029-02-14',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2026-11-30',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 9,
                'license_no' => 'DL-2022-009-PH',
                'license_expiry' => '2027-12-05',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2025-07-25',
                'country_id' => '167',
                
            ],
            [
                'employee_id' => 10,
                'license_no' => 'DL-2023-010-PH',
                'license_expiry' => '2028-10-01',
                'status' => 'Active',
                'license_class' => 'Professional',
                'medical_expiry' => '2026-05-15',
                'country_id' => '167',
                
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}