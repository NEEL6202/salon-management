<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Salon;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salons = Salon::all();
        
        foreach ($salons as $salon) {
            $categories = Category::where('salon_id', $salon->id)->get();
            
            $products = [
                [
                    'name' => 'Professional Hair Shampoo',
                    'description' => 'High-quality professional hair shampoo',
                    'sku' => 'PROD-001',
                    'price' => 25.00,
                    'cost_price' => 15.00,
                    'stock_quantity' => 50,
                    'min_stock_level' => 10,
                    'unit' => 'bottle',
                    'is_active' => true,
                    'is_featured' => true,
                    'category_id' => $categories->where('name', 'Hair Care')->first()->id ?? null,
                ],
                [
                    'name' => 'Nail Polish Set',
                    'description' => 'Professional nail polish set with 6 colors',
                    'sku' => 'PROD-002',
                    'price' => 35.00,
                    'cost_price' => 20.00,
                    'stock_quantity' => 30,
                    'min_stock_level' => 5,
                    'unit' => 'pack',
                    'is_active' => true,
                    'is_featured' => false,
                    'category_id' => $categories->where('name', 'Nail Care')->first()->id ?? null,
                ],
                [
                    'name' => 'Facial Cleanser',
                    'description' => 'Gentle facial cleanser for all skin types',
                    'sku' => 'PROD-003',
                    'price' => 28.00,
                    'cost_price' => 18.00,
                    'stock_quantity' => 40,
                    'min_stock_level' => 8,
                    'unit' => 'bottle',
                    'is_active' => true,
                    'is_featured' => true,
                    'category_id' => $categories->where('name', 'Skin Care')->first()->id ?? null,
                ],
                [
                    'name' => 'Makeup Brush Set',
                    'description' => 'Professional makeup brush set with 12 brushes',
                    'sku' => 'PROD-004',
                    'price' => 45.00,
                    'cost_price' => 25.00,
                    'stock_quantity' => 25,
                    'min_stock_level' => 5,
                    'unit' => 'pack',
                    'is_active' => true,
                    'is_featured' => false,
                    'category_id' => $categories->where('name', 'Makeup')->first()->id ?? null,
                ],
                [
                    'name' => 'Hair Dryer',
                    'description' => 'Professional hair dryer with multiple settings',
                    'sku' => 'PROD-005',
                    'price' => 120.00,
                    'cost_price' => 80.00,
                    'stock_quantity' => 15,
                    'min_stock_level' => 3,
                    'unit' => 'piece',
                    'is_active' => true,
                    'is_featured' => true,
                    'category_id' => $categories->where('name', 'Accessories')->first()->id ?? null,
                ],
                [
                    'name' => 'Perfume - Floral',
                    'description' => 'Elegant floral perfume for women',
                    'sku' => 'PROD-006',
                    'price' => 85.00,
                    'cost_price' => 50.00,
                    'stock_quantity' => 20,
                    'min_stock_level' => 5,
                    'unit' => 'bottle',
                    'is_active' => true,
                    'is_featured' => false,
                    'category_id' => $categories->where('name', 'Fragrances')->first()->id ?? null,
                ],
            ];

            foreach ($products as $productData) {
                if ($productData['category_id']) {
                    Product::updateOrCreate(
                        ['sku' => $productData['sku']],
                        [
                            'salon_id' => $salon->id,
                            'name' => $productData['name'],
                            'description' => $productData['description'],
                            'price' => $productData['price'],
                            'cost_price' => $productData['cost_price'],
                            'stock_quantity' => $productData['stock_quantity'],
                            'min_stock_level' => $productData['min_stock_level'],
                            'unit' => $productData['unit'],
                            'is_active' => $productData['is_active'],
                            'is_featured' => $productData['is_featured'],
                            'category_id' => $productData['category_id'],
                        ]
                    );
                }
            }
        }
    }
}
