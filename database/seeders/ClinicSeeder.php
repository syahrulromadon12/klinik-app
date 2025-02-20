<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clinic;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinic = [
            ['name' => 'Poli Umum', 'code' => 'KSS001',],
            ['name' => 'Poli Gigi', 'code' => 'KSS002',],
            ['name' => 'Poli Kandungan', 'code' => 'KSS003',],
            ['name' => 'Poli Anak', 'code' => 'KSS004',],
            ['name' => 'Poli Jantung', 'code' => 'KSS005',],
        ];

        foreach ($clinic as $clinic) {
            Clinic::create($clinic);
        }
    }
}
