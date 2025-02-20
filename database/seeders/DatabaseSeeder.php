<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MedicalCategorySeeder::class,
            ClinicSeeder::class,
        ]);

        \App\Models\User::factory(10)->create();
        \App\Models\Patients::factory(10)->create();
        \App\Models\MedicalStaff::factory(10)->create();
        \App\Models\Appointment::factory(10)->create();
        \App\Models\MedicalRecord::factory(10)->create();
        \App\Models\Payment::factory(10)->create();
        \App\Models\Prescription::factory(10)->create();
        \App\Models\Medicine::factory(20)->create();
        \App\Models\PharmacyOrder::factory(5)->create();
        \App\Models\PrescriptionItem::factory(15)->create();
        \App\Models\Queue::factory(10)->create();
    }
}
