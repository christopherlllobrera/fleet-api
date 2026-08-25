<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('1024fleetMLI');

        // 1. Ensure user_id = 1 (Admin User) exists and is set up properly
        $adminUser = User::updateOrCreate(
            ['id' => 1],
            [
                'employee_no' => 'EMP001',
                'name' => 'John Christopher L. Llobrera',
                'email' => 'jclllobrera@miescor.ph',
                'password' => 'password101',
                'email_verified_at' => now(),
            ]
        );

        // Seed Departments (Ensure defaults exist)
        $departmentsData = [
            'CORP_ICT' => 'Corporate ICT',
            'FINANCE' => 'Finance',
            'TRANS_TWE' => 'Transport and TWE Services',
            'MLI_MER_TRANS' => 'MLI Meralco Transport',
            'MOTORPOOL' => 'Motorpool',
        ];

        $createdDepartments = [];
        foreach ($departmentsData as $code => $desc) {
            $createdDepartments[$code] = Department::firstOrCreate(
                ['department_no' => $code],
                ['department_description' => $desc]
            );
        }

        // Seed Admin Position
        $adminPosition = Position::firstOrCreate(
            ['position_no' => 'POS001'],
            [
                'position_description' => 'Senior Operations Manager',
                'department_id' => $createdDepartments['CORP_ICT']->id,
            ]
        );

        // Ensure Admin Employee exists
        Employee::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'employee_no' => '10030947',
                'first_name' => 'John Christopher',
                'middle_name' => 'L.',
                'last_name' => 'Llobrera',
                'email' => 'jclllobrera@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments['CORP_ICT']->id,
                'position_id' => $adminPosition->id,
                'date_hired' => '2022-01-15',
                'regularization_date' => '2022-04-15',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Senior Operations Manager',
                'status' => 'active',
            ]
        );

        // 2. Parse CSV and seed other employees
        $csvPath = database_path('seeders/CSV/FleetUsers.csv');
        $csvEmployees = [];
        $csvDrivers = [];

        if (file_exists($csvPath)) {
            $file = fopen($csvPath, 'r');
            $isDriverSection = false;

            while (($row = fgetcsv($file)) !== false) {
                if (empty($row) || ! isset($row[0]) || trim($row[0]) === '') {
                    continue;
                }

                if (trim($row[0]) === 'PERNR') {
                    if (isset($row[2]) && trim($row[2]) === 'Position') {
                        $isDriverSection = true;
                    }

                    continue;
                }

                if (! $isDriverSection) {
                    $csvEmployees[] = [
                        'pernr' => trim($row[0]),
                        'name' => trim($row[1]),
                        'job_title' => trim($row[2]),
                        'email' => (empty($row[3]) || trim($row[3]) === 'N/A') ? null : trim($row[3]),
                        'roles' => isset($row[4]) ? trim($row[4]) : null,
                    ];
                } else {
                    $csvDrivers[] = [
                        'pernr' => trim($row[0]),
                        'name' => trim($row[1]),
                        'position' => trim($row[2]),
                    ];
                }
            }
            fclose($file);
        }

        // Helper to create employee from CSV data
        $createEmployeeFromCsv = function (array $data, string $positionDesc, string $deptCode) use ($createdDepartments, $defaultPassword) {
            $name = $data['name'];
            $parts = explode(' ', $name);
            if (count($parts) > 1) {
                $lastName = array_pop($parts);
                $firstName = implode(' ', $parts);
                $middleName = '';
            } else {
                $firstName = $name;
                $lastName = '';
                $middleName = '';
            }

            $email = ! empty($data['email']) ? $data['email'] : null;

            // Find or create user only if employee has an email
            $user = null;
            if ($email) {
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'employee_no' => $data['pernr'],
                        'name' => $name,
                        'password' => $defaultPassword,
                        'email_verified_at' => now(),
                    ]
                );
            }

            // Find or create position
            $position = Position::firstOrCreate(
                ['position_description' => $positionDesc],
                [
                    'position_no' => 'POS_'.strtoupper(substr(str_replace(' ', '_', $positionDesc), 0, 10)).'_'.rand(100, 999),
                    'department_id' => $createdDepartments[$deptCode]->id,
                ]
            );

            // Find or create employee
            Employee::firstOrCreate(
                ['employee_no' => $data['pernr']],
                [
                    'user_id' => $user?->id,
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'company_id' => 3,
                    'department_id' => $createdDepartments[$deptCode]->id,
                    'position_id' => $position->id,
                    'date_hired' => now()->subYears(2)->format('Y-m-d'),
                    'regularization_date' => now()->subYears(2)->addMonths(6)->format('Y-m-d'),
                    'is_active' => true,
                    'data_privacy_consent' => true,
                    'remarks' => $positionDesc,
                    'status' => 'active',
                ]
            );
        };

        // Create standard employees
        foreach ($csvEmployees as $emp) {
            $deptCode = 'MOTORPOOL'; // Default
            $jobTitle = strtoupper($emp['job_title']);
            if (str_contains($jobTitle, 'FINANCE')) {
                $deptCode = 'FINANCE';
            } elseif (str_contains($jobTitle, 'ICT') || str_contains($jobTitle, 'IT')) {
                $deptCode = 'CORP_ICT';
            } elseif (str_contains($jobTitle, 'MERALCO')) {
                $deptCode = 'MLI_MER_TRANS';
            } elseif (str_contains($jobTitle, 'TRANSPORT') || str_contains($jobTitle, 'TWE')) {
                $deptCode = 'TRANS_TWE';
            } elseif (str_contains($jobTitle, 'DISPATCH') || str_contains($jobTitle, 'MOTORPOOL') || str_contains($jobTitle, 'FLEET') || str_contains($jobTitle, 'LEAD')) {
                $deptCode = 'MOTORPOOL';
            }
            $createEmployeeFromCsv($emp, $emp['job_title'], $deptCode);
        }

        // Create driver employees
        foreach ($csvDrivers as $drv) {
            $createEmployeeFromCsv([
                'pernr' => $drv['pernr'],
                'name' => $drv['name'],
                'email' => null,
            ], 'Driver', 'MOTORPOOL');
        }

        // Create related records (addresses, contacts, etc.) for each employee
        $this->call(EmployeeRelatedSeeder::class);
    }
}
