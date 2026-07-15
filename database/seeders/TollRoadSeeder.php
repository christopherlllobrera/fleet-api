<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TollRoadSeeder extends Seeder
{
    public function run(): void
    {
        DB::unprepared("
            INSERT INTO `toll_roads` (`id`, `name`, `operator`, `region`, `is_active`, `created_at`, `updated_at`) VALUES
            (1, 'TPLEX', 'SMC TPLEX Corporation', 'CENTRAL LUZON', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (2, 'NLEX - SCTEX', 'MPTC', 'CENTRAL LUZON', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (3, 'Skyway Stage 3', 'SMC', 'CALABARZON', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (4, 'NLEX Connector', 'MPTC', 'METRO MANILA', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (5, 'NLEX Harbor Link', 'NLEX Corporation', 'CENTRAL LUZON', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (6, 'NAIAX', 'SMC', 'METRO MANILA', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (7, 'SLEX-SKYWAY-MCX', 'SAN MIGUEL HOLDINGS CORPORATION', 'SOUTH LUZON', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (8, 'CAVITEX', 'PEA Tollway Corporation', 'CALABARZON', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (9, 'CALAX', 'MPCALA Holdings Incorporated', 'CAVITE-LAGUNA', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00'),
            (10, 'STAR', 'STAR Tollway Corporation', 'BATANGAS', 1, '2025-04-02 00:00:00', '2025-04-03 00:00:00');
         ");
    }
}
