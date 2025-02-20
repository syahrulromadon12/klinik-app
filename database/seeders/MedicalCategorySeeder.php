<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalCategory;

class MedicalCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'General Medicine'],
            ['name' => 'Pediatrics'],
            ['name' => 'Dermatology'],
            ['name' => 'Cardiology'],
            ['name' => 'Neurology'],
        ];

        foreach ($categories as $category) { // Ganti typo dari $categoriy ke $category
            MedicalCategory::create($category);
        }
    }
}
