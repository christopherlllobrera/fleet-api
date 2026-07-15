<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Users
        $usersData = [
            [
                'name' => 'John Christopher L. Llobrera',
                'email' => 'jclllobrera@miescor.ph',
            ],
            [
                'name' => 'Maria Garcia Lopez',
                'email' => 'maria.lopez@miescor.ph',
            ],
            [
                'name' => 'Carlos Reyes Martinez',
                'email' => 'carlos.martinez@miescor.ph',
            ],
            [
                'name' => 'Angela Cruz Fernandez',
                'email' => 'angela.fernandez@miescor.ph',
            ],
            [
                'name' => 'Robert Aquino Villanueva',
                'email' => 'robert.villanueva@miescor.ph',
            ],
            [
                'name' => 'Rosa Navarro Santos',
                'email' => 'rosa.santos@miescor.ph',
            ],
            [
                'name' => 'Miguel Punzalan Ocampo',
                'email' => 'miguel.ocampo@miescor.ph',
            ],
            [
                'name' => 'Isabella Romero Gonzales',
                'email' => 'isabella.gonzales@miescor.ph',
            ],
            [
                'name' => 'Daniel Castillo Diaz',
                'email' => 'daniel.diaz@miescor.ph',
            ],
            [
                'name' => 'Patricia Soriano Ramos',
                'email' => 'patricia.ramos@miescor.ph',
            ],
        ];

        $createdUsers = [];
        $defaultPassword = Hash::make('1024fleetMLI');

        foreach ($usersData as $userData) {
            $createdUsers[] = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $defaultPassword,
                'email_verified_at' => now(),
            ]);
        }

        // Seed Departments
        $departments = [
            [
                'department_no' => 'DEPT001',
                'department_description' => 'Operations and Management',
            ],
            [
                'department_no' => 'DEPT002',
                'department_description' => 'Logistics and Fleet Coordination',
            ],
            [
                'department_no' => 'DEPT003',
                'department_description' => 'Driver and Vehicle Management',
            ],
            [
                'department_no' => 'DEPT004',
                'department_description' => 'Finance and Administration',
            ],
        ];

        $createdDepartments = [];
        foreach ($departments as $dept) {
            $createdDepartments[] = Department::create($dept);
        }

        // Seed Positions
        $positions = [
            [
                'position_no' => 'POS001',
                'position_description' => 'Senior Operations Manager',
                'department_id' => $createdDepartments[0]->id,
            ],
            [
                'position_no' => 'POS002',
                'position_description' => 'Fleet Coordinator',
                'department_id' => $createdDepartments[1]->id,
            ],
            [
                'position_no' => 'POS003',
                'position_description' => 'Professional Driver',
                'department_id' => $createdDepartments[2]->id,
            ],
            [
                'position_no' => 'POS004',
                'position_description' => 'Administrative Assistant',
                'department_id' => $createdDepartments[3]->id,
            ],
            [
                'position_no' => 'POS005',
                'position_description' => 'Logistics Officer',
                'department_id' => $createdDepartments[1]->id,
            ],
            [
                'position_no' => 'POS006',
                'position_description' => 'Data Analyst',
                'department_id' => $createdDepartments[0]->id,
            ],
            [
                'position_no' => 'POS007',
                'position_description' => 'Human Resources Officer',
                'department_id' => $createdDepartments[3]->id,
            ],
        ];

        $createdPositions = [];
        foreach ($positions as $pos) {
            $createdPositions[] = Position::create($pos);
        }

        // Seed Employees
        $employees = [
            [
                'user_id' => $createdUsers[0]->id,
                'employee_no' => 'EMP001',
                'first_name' => 'John Christopher',
                'middle_name' => 'L.',
                'last_name' => 'Llobrera',
                'email' => 'John Christopher L. Llobrera',
                'company_id' => 1,
                'department_id' => $createdDepartments[0]->id,
                'position_id' => $createdPositions[0]->id,
                'date_hired' => '2022-01-15',
                'regularization_date' => '2022-04-15',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Senior Operations Manager',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[1]->id,
                'employee_no' => 'EMP002',
                'first_name' => 'Maria',
                'middle_name' => 'Garcia',
                'last_name' => 'Lopez',
                'email' => 'maria.lopez@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[1]->id,
                'position_id' => $createdPositions[1]->id,
                'date_hired' => '2022-03-20',
                'regularization_date' => '2022-06-20',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Fleet Coordinator',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[2]->id,
                'employee_no' => 'EMP003',
                'first_name' => 'Carlos',
                'middle_name' => 'Reyes',
                'last_name' => 'Martinez',
                'email' => 'carlos.martinez@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[2]->id,
                'position_id' => $createdPositions[2]->id,
                'date_hired' => '2021-06-10',
                'regularization_date' => '2021-09-10',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Professional Driver',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[3]->id,
                'employee_no' => 'EMP004',
                'first_name' => 'Angela',
                'middle_name' => 'Cruz',
                'last_name' => 'Fernandez',
                'email' => 'angela.fernandez@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[1]->id,
                'position_id' => $createdPositions[4]->id,
                'date_hired' => '2022-05-01',
                'regularization_date' => '2022-08-01',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Logistics Officer',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[4]->id,
                'employee_no' => 'EMP005',
                'first_name' => 'Robert',
                'middle_name' => 'Aquino',
                'last_name' => 'Villanueva',
                'email' => 'robert.villanueva@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[2]->id,
                'position_id' => $createdPositions[2]->id,
                'date_hired' => '2021-09-15',
                'regularization_date' => '2021-12-15',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Professional Driver',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[5]->id,
                'employee_no' => 'EMP006',
                'first_name' => 'Rosa',
                'middle_name' => 'Navarro',
                'last_name' => 'Santos',
                'email' => 'rosa.santos@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[3]->id,
                'position_id' => $createdPositions[3]->id,
                'date_hired' => '2023-01-10',
                'regularization_date' => '2023-04-10',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Administrative Assistant',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[6]->id,
                'employee_no' => 'EMP007',
                'first_name' => 'Miguel',
                'middle_name' => 'Punzalan',
                'last_name' => 'Ocampo',
                'email' => 'miguel.ocampo@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[1]->id,
                'position_id' => $createdPositions[1]->id,
                'date_hired' => '2022-07-20',
                'regularization_date' => '2022-10-20',
                'is_active' => false,
                'data_privacy_consent' => true,
                'remarks' => 'On leave',
                'status' => 'inactive',
            ],
            [
                'user_id' => $createdUsers[7]->id,
                'employee_no' => 'EMP008',
                'first_name' => 'Isabella',
                'middle_name' => 'Romero',
                'last_name' => 'Gonzales',
                'email' => 'isabella.gonzales@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[0]->id,
                'position_id' => $createdPositions[5]->id,
                'date_hired' => '2023-02-14',
                'regularization_date' => '2023-05-14',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Data Analyst',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[8]->id,
                'employee_no' => 'EMP009',
                'first_name' => 'Daniel',
                'middle_name' => 'Castillo',
                'last_name' => 'Diaz',
                'email' => 'daniel.diaz@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[2]->id,
                'position_id' => $createdPositions[2]->id,
                'date_hired' => '2022-04-05',
                'regularization_date' => '2022-07-05',
                'is_active' => true,
                'data_privacy_consent' => true,
                'remarks' => 'Professional Driver',
                'status' => 'active',
            ],
            [
                'user_id' => $createdUsers[9]->id,
                'employee_no' => 'EMP010',
                'first_name' => 'Patricia',
                'middle_name' => 'Soriano',
                'last_name' => 'Ramos',
                'email' => 'patricia.ramos@miescor.ph',
                'company_id' => 1,
                'department_id' => $createdDepartments[3]->id,
                'position_id' => $createdPositions[6]->id,
                'date_hired' => '2023-03-01',
                'regularization_date' => null,
                'is_active' => true,
                'data_privacy_consent' => false,
                'remarks' => 'Probationary',
                'status' => 'active',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        // Create related records (addresses, contacts, etc.) for each employee
        $this->call(EmployeeRelatedSeeder::class);
    }
}