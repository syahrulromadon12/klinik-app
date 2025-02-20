<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'description' => 'Full access to the system.',
            ],
            [
                'name' => 'Doctor',
                'description' => 'Can manage patients and appointments.',
            ],
            [
                'name' => 'Nurse',
                'description' => 'Can assist doctors and manage patient records.',
            ],
            [
                'name' => 'Receptionist',
                'description' => 'Handles appointments and patient registrations.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],  // Cek jika sudah ada
                ['description' => $role['description']]
            );
        }
    }
}
