<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kamera',
                'description' => 'Peralatan kamera untuk fotografi dan videografi profesional',
                'is_active' => true
            ],
            [
                'name' => 'Audio',
                'description' => 'Peralatan audio untuk recording dan broadcasting',
                'is_active' => true
            ],
            [
                'name' => 'Drone',
                'description' => 'Drone untuk aerial photography dan videography',
                'is_active' => true
            ],
            [
                'name' => 'Lighting',
                'description' => 'Peralatan pencahayaan untuk studio dan outdoor',
                'is_active' => true
            ],
            [
                'name' => 'Tripod & Support',
                'description' => 'Tripod dan peralatan pendukung kamera',
                'is_active' => true
            ],
            [
                'name' => 'Storage & Memory',
                'description' => 'Memory card dan perangkat penyimpanan',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}