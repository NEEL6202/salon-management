<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Salon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salons = Salon::all();
        
        foreach ($salons as $salon) {
            $categories = [
                [
                    'name' => 'Hair Care',
                    'description' => 'Hair care products and services',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Nail Care',
                    'description' => 'Nail care products and services',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Skin Care',
                    'description' => 'Skin care products and services',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Makeup',
                    'description' => 'Makeup products and services',
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Accessories',
                    'description' => 'Beauty accessories and tools',
                    'is_active' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Fragrances',
                    'description' => 'Perfumes and fragrances',
                    'is_active' => true,
                    'sort_order' => 6,
                ],
            ];

            foreach ($categories as $categoryData) {
                Category::create([
                    'salon_id' => $salon->id,
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                    'is_active' => $categoryData['is_active'],
                    'sort_order' => $categoryData['sort_order'],
                ]);
            }
        }
    }
}
