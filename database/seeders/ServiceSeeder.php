<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Salon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salons = Salon::all();
        
        foreach ($salons as $salon) {
            $services = [
                [
                    'name' => 'Haircut & Styling',
                    'description' => 'Professional haircut and styling service',
                    'price' => 45.00,
                    'duration' => 60,
                    'category' => 'hair',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Hair Coloring',
                    'description' => 'Professional hair coloring service',
                    'price' => 85.00,
                    'duration' => 120,
                    'category' => 'hair',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Manicure',
                    'description' => 'Classic manicure service',
                    'price' => 25.00,
                    'duration' => 45,
                    'category' => 'nails',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Pedicure',
                    'description' => 'Classic pedicure service',
                    'price' => 35.00,
                    'duration' => 60,
                    'category' => 'nails',
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                [
                    'name' => 'Facial Treatment',
                    'description' => 'Rejuvenating facial treatment',
                    'price' => 65.00,
                    'duration' => 90,
                    'category' => 'facial',
                    'is_active' => true,
                    'sort_order' => 5,
                ],
                [
                    'name' => 'Massage Therapy',
                    'description' => 'Relaxing massage therapy',
                    'price' => 75.00,
                    'duration' => 60,
                    'category' => 'massage',
                    'is_active' => true,
                    'sort_order' => 6,
                ],
                [
                    'name' => 'Makeup Application',
                    'description' => 'Professional makeup application',
                    'price' => 55.00,
                    'duration' => 90,
                    'category' => 'makeup',
                    'is_active' => true,
                    'sort_order' => 7,
                ],
            ];

            foreach ($services as $serviceData) {
                Service::create([
                    'salon_id' => $salon->id,
                    'name' => $serviceData['name'],
                    'description' => $serviceData['description'],
                    'price' => $serviceData['price'],
                    'duration' => $serviceData['duration'],
                    'category' => $serviceData['category'],
                    'is_active' => $serviceData['is_active'],
                    'sort_order' => $serviceData['sort_order'],
                ]);
            }
        }
    }
}
