<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            ['id' => 1, 'department_no' => 'MIEA100', 'department_description' => 'OFFICE OF THE PRESIDENT'],
            ['id' => 2, 'department_no' => 'MIEB100', 'department_description' => 'CORPORATE SERVICES GROUP'],
            ['id' => 3, 'department_no' => 'MIEB110', 'department_description' => 'ADMINISTRATIVE SERVICES'],
            ['id' => 4, 'department_no' => 'MIEB120', 'department_description' => 'CORPORATE PROCESS MANAGEMENT'],
            ['id' => 5, 'department_no' => 'MIEC100', 'department_description' => 'CORPORATE AUDIT'],
            ['id' => 6, 'department_no' => 'MIED100', 'department_description' => 'SUPPLY CHAIN  MANAGEMENT'],
            ['id' => 7, 'department_no' => 'MIED110', 'department_description' => 'PROCUREMENT TEAM 1'],
            ['id' => 8, 'department_no' => 'MIED120', 'department_description' => 'SUPPLY CHAIN OPERATIONS SUPPORT'],
            ['id' => 9, 'department_no' => 'MIED140', 'department_description' => 'SUPPLY CHAIN BUSINESS PARTNERING GROUP'],
            ['id' => 10, 'department_no' => 'MIED150', 'department_description' => 'WAREHOUSING AND MATERIALS MANAGEMENT'],
            ['id' => 11, 'department_no' => 'MIEE100', 'department_description' => 'FINANCE'],
            ['id' => 12, 'department_no' => 'MIEE110', 'department_description' => 'CONTROLLERSHIP'],
            ['id' => 13, 'department_no' => 'MIEE120', 'department_description' => 'FINANCIAL PLANNING, SYSTEMS AND PROCESS MANAGEMENT'],
            ['id' => 14, 'department_no' => 'MIEE130', 'department_description' => 'TREASURY'],
            ['id' => 15, 'department_no' => 'MIEE140', 'department_description' => 'MBI - INTERCOMPANY CHARGES'],
            ['id' => 16, 'department_no' => 'MIEE150', 'department_description' => 'MLI - INTERCOMPANY CHARGES'],
            ['id' => 17, 'department_no' => 'MIEE170', 'department_description' => 'PROJECT COST CONROL'],
            ['id' => 18, 'department_no' => 'MIEF100', 'department_description' => 'LEGAL'],
            ['id' => 19, 'department_no' => 'MIEG100', 'department_description' => 'ICT'],
            ['id' => 20, 'department_no' => 'MIEG110', 'department_description' => 'ICT GOVERNANCE AND COMPLIANCE'],
            ['id' => 21, 'department_no' => 'MIEG120', 'department_description' => 'ICT DELIVERY AND TRANSFORMTION'],
            ['id' => 22, 'department_no' => 'MIEG130', 'department_description' => 'ICT OPERATIONS'],
            ['id' => 23, 'department_no' => 'MIEH100', 'department_description' => 'CORPORATE HR AND TRANSFORMATION'],
            ['id' => 24, 'department_no' => 'MIEH110', 'department_description' => 'COMPENSATION AND BENEFITS DESIGN AND IMPLEMENTATION'],
            ['id' => 25, 'department_no' => 'MIEH140', 'department_description' => 'TALENT DEVELOPMENT AND ENGAGEMENT'],
            ['id' => 26, 'department_no' => 'MIEH160', 'department_description' => 'HR BUSINESS PARTNERING GROUP'],
            ['id' => 27, 'department_no' => 'MIEH170', 'department_description' => 'TALENT ACQUISITION AND RESOURCING'],
            ['id' => 28, 'department_no' => 'MIEH180', 'department_description' => 'ORGANIZATIONAL DEVELOPMENT'],
            ['id' => 29, 'department_no' => 'MIEI100', 'department_description' => 'CORPORATE LABOR RELATIONS'],
            ['id' => 30, 'department_no' => 'MIEI110', 'department_description' => 'LABOR RELATIONS'],
            ['id' => 31, 'department_no' => 'MIEI120', 'department_description' => 'SECURITY SERVICES'],
            ['id' => 32, 'department_no' => 'MIEK100', 'department_description' => 'OCCUPATIONAL SAFETY AND SUSTAINABILITY MANAGEMENT'],
            ['id' => 33, 'department_no' => 'MIEL100', 'department_description' => 'QUALITY ASSURANCE AND CONTROL'],
            ['id' => 34, 'department_no' => 'MIEL110', 'department_description' => 'PROCESS AND STANDARDS'],
            ['id' => 35, 'department_no' => 'MIEL120', 'department_description' => 'QUALITY CONTROL'],
            ['id' => 36, 'department_no' => 'MIEN100', 'department_description' => 'PROJECT ENGINEERING AND EXECUTION'],
            ['id' => 37, 'department_no' => 'MIEO100', 'department_description' => 'LOGISTICS MANAGEMENT'],
            ['id' => 38, 'department_no' => 'MIEP100', 'department_description' => 'COMMERCIAL SERVICES'],
            ['id' => 39, 'department_no' => 'MIEQ100', 'department_description' => 'OPERATIONS GROUP'],
        ]);
    }
}
