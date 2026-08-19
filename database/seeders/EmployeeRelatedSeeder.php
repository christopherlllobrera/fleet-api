<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Country;
use App\Models\Employee;
use App\Models\EmployeeAddress;
use App\Models\EmployeeCertification;
use App\Models\EmployeeContact;
use App\Models\EmployeeDependent;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeGovernmentInfo;
use App\Models\EmployeeInsurance;
use App\Models\EmployeeProfile;
use App\Models\Municipality;
use App\Models\Province;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeRelatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Address - pick existing location ids when available
            $countryId = Country::inRandomOrder()->value('id');
            $province = Province::inRandomOrder()->first();
            $provinceId = $province?->id;
            $regionId = $province?->region_id;
            $municipality = $provinceId ? Municipality::where('province_id', $provinceId)->inRandomOrder()->first() : null;
            $municipalityId = $municipality?->id;
            $barangay = $municipalityId ? Barangay::where('municipality_id', $municipalityId)->inRandomOrder()->first() : null;
            $barangayId = $barangay?->id;

            EmployeeAddress::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'type' => 'permanent',
                    'country_id' => $countryId,
                    'region_id' => $regionId,
                    'province_id' => $provinceId,
                    'municipality_id' => $municipalityId,
                    'barangay_id' => $barangayId,
                    'address' => $faker->address(),
                    'is_same_as_permanent' => (bool) $faker->boolean(50),
                ]
            );

            // Certification
            EmployeeCertification::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'institution' => $faker->company(),
                    'license' => $faker->word(),
                    'license_number' => $faker->bothify('LIC-####'),
                    'date_issued' => $faker->date(),
                    'date_expiry' => $faker->date(),
                ]
            );

            // Contact
            EmployeeContact::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'type' => $faker->randomElement(['mobile', 'home', 'work']),
                    'value' => $faker->phoneNumber(),
                ]
            );

            // Dependent
            EmployeeDependent::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'full_name' => $faker->name(),
                    'date_of_birth' => $faker->date(),
                    'relationship' => $faker->randomElement(['spouse', 'child', 'parent', 'sibling']),
                ]
            );

            // Education (insert directly to avoid table name issues)
            if (! DB::table('employee_educations')->where('employee_id', $employee->id)->exists()) {
                DB::table('employee_educations')->insert([
                    'employee_id' => $employee->id,
                    'degree_type' => $faker->randomElement(['Bachelor', 'Master', 'Doctorate', 'Associate']),
                    'degree_name' => $faker->word().' Studies',
                    'school_id' => null,
                    'start_date' => $faker->date(),
                    'end_date' => $faker->date(),
                    'duration_of_course' => $faker->numberBetween(1, 6),
                    'final_grade' => (string) $faker->randomFloat(2, 1, 4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Emergency Contact
            EmployeeEmergencyContact::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'name' => $faker->name(),
                    'relationship' => $faker->randomElement(['spouse', 'parent', 'sibling', 'friend']),
                    'contact_no' => $faker->phoneNumber(),
                ]
            );

            // Government Info
            EmployeeGovernmentInfo::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'tin_no' => $faker->bothify('TIN-########'),
                    'sss_no' => $faker->bothify('SSS-#########'),
                    'pag_ibig_no' => $faker->bothify('PAG-########'),
                    'philhealth_no' => $faker->bothify('PH-########'),
                ]
            );

            // Profile
            EmployeeProfile::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'date_of_birth' => $faker->date(),
                    'gender' => $faker->randomElement(['male', 'female', 'other']),
                    'civil_status' => $faker->randomElement(['single', 'married', 'widowed', 'separated']),
                    'address' => $faker->address(),
                    'suffix_name' => null,
                    'place_of_birth' => $faker->city(),
                    'nationality_id' => null,
                    'personal_number' => $faker->bothify('PN-#####'),
                    'date_of_marriage' => null,
                    'spouse_name' => null,
                    'spouse_date_of_birth' => null,
                    'spouse_place_of_birth' => null,
                    'mother_name' => $faker->name('female'),
                    'mother_date_of_birth' => $faker->date(),
                    'father_name' => $faker->name('male'),
                    'father_date_of_birth' => $faker->date(),
                    'date_of_death' => null,
                    'date_of_separation' => null,
                ]
            );

            // Insurance
            EmployeeInsurance::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'provider' => $faker->company(),
                    'med_card_no' => $faker->bothify('MC-#######'),
                    'med_card_policy_no' => $faker->bothify('MP-#######'),
                    'valid_until' => $faker->date(),
                ]
            );
        }
    }
}
