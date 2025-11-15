<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Product;
use App\Models\Category;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyReward;
use App\Models\GiftCard;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SalonDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get the test salon
        $salon = Salon::where('email', 'info@beautyhavensalon.com')->first();
        
        if (!$salon) {
            $this->command->error('Test salon not found. Please run TestSalonOwnerSeeder first.');
            return;
        }

        // Get users
        $salonOwner = User::where('email', 'sarah@beautyhavensalon.com')->first();
        $manager = User::where('email', 'mike@beautyhavensalon.com')->first();
        $employee = User::where('email', 'emma@beautyhavensalon.com')->first();
        
        // Create some test customers
        $customers = [];
        for ($i = 1; $i <= 5; $i++) {
            $customer = User::create([
                'name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'gender' => rand(0, 1) ? 'male' : 'female',
                'date_of_birth' => date('Y-m-d', strtotime('-' . rand(18, 65) . ' years')),
                'address' => rand(100, 999) . ' Main Street, City',
                'salon_id' => $salon->id,
                'status' => 'active',
                'created_by' => $salonOwner->id,
            ]);
            
            $customer->assignRole('customer');
            $customers[] = $customer;
        }

        // Create categories
        $categories = [];
        $categoryNames = ['hair', 'nails', 'facial', 'massage', 'makeup'];
        foreach ($categoryNames as $categoryName) {
            $category = Category::firstOrCreate([
                'name' => ucfirst($categoryName),
                'salon_id' => $salon->id,
            ], [
                'description' => 'Category for ' . $categoryName,
                'is_active' => true,
            ]);
            $categories[] = $category;
        }

        // Create services
        $services = [];
        $serviceData = [
            ['name' => 'Haircut & Style', 'price' => 45.00, 'duration' => 60, 'category' => 'hair'],
            ['name' => 'Hair Coloring', 'price' => 85.00, 'duration' => 120, 'category' => 'hair'],
            ['name' => 'Manicure', 'price' => 25.00, 'duration' => 45, 'category' => 'nails'],
            ['name' => 'Pedicure', 'price' => 35.00, 'duration' => 60, 'category' => 'nails'],
            ['name' => 'Facial Treatment', 'price' => 65.00, 'duration' => 90, 'category' => 'facial'],
            ['name' => 'Waxing', 'price' => 40.00, 'duration' => 30, 'category' => 'other'],
            ['name' => 'Massage Therapy', 'price' => 75.00, 'duration' => 90, 'category' => 'massage'],
            ['name' => 'Makeup Application', 'price' => 55.00, 'duration' => 60, 'category' => 'makeup'],
        ];

        foreach ($serviceData as $data) {
            $service = Service::create([
                'name' => $data['name'],
                'description' => 'Professional ' . strtolower($data['name']) . ' service',
                'price' => $data['price'],
                'duration' => $data['duration'],
                'category' => $data['category'],
                'salon_id' => $salon->id,
                'is_active' => true,
            ]);
            $services[] = $service;
        }

        // Create products
        $products = [];
        $productData = [
            ['name' => 'Shampoo', 'price' => 12.99, 'stock_quantity' => 50],
            ['name' => 'Conditioner', 'price' => 14.99, 'stock_quantity' => 40],
            ['name' => 'Hair Serum', 'price' => 24.99, 'stock_quantity' => 30],
            ['name' => 'Nail Polish', 'price' => 8.99, 'stock_quantity' => 100],
            ['name' => 'Face Mask', 'price' => 19.99, 'stock_quantity' => 25],
            ['name' => 'Moisturizer', 'price' => 29.99, 'stock_quantity' => 20],
            ['name' => 'Sunscreen', 'price' => 22.99, 'stock_quantity' => 35],
            ['name' => 'Lip Balm', 'price' => 5.99, 'stock_quantity' => 75],
        ];

        foreach ($productData as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'description' => 'High-quality ' . strtolower($data['name']),
                'price' => $data['price'],
                'stock_quantity' => $data['stock_quantity'],
                'category_id' => $categories[array_rand($categories)]->id,
                'salon_id' => $salon->id,
                'sku' => strtoupper(Str::random(6)),
                'is_active' => true,
            ]);
            $products[] = $product;
        }

        // Create appointments for the past month
        for ($i = 0; $i < 30; $i++) {
            $customer = $customers[array_rand($customers)];
            $service = $services[array_rand($services)];
            $employeeUser = [null, $employee][rand(0, 1)]; // Randomly assign employee or leave null
            
            $appointmentDate = now()->subDays(rand(1, 30))->setTime(rand(9, 17), 0);
            $endTime = $appointmentDate->copy()->addMinutes($service->duration);
            
            $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            $status = $statuses[array_rand($statuses)];
            
            $paymentStatuses = ['pending', 'paid', 'refunded'];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            
            Appointment::create([
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'employee_id' => $employeeUser ? $employeeUser->id : null,
                'salon_id' => $salon->id,
                'appointment_date' => $appointmentDate,
                'end_time' => $endTime,
                'notes' => 'Appointment notes for ' . $service->name,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'total_amount' => $service->price,
                'final_amount' => $service->price,
            ]);
        }

        // Create orders for the past month
        for ($i = 0; $i < 20; $i++) {
            $customer = $customers[array_rand($customers)];
            
            $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
            $orderStatus = $orderStatuses[array_rand($orderStatuses)];
            
            $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            
            $order = Order::create([
                'customer_id' => $customer->id,
                'salon_id' => $salon->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(6)),
                'status' => $orderStatus,
                'payment_status' => $paymentStatus,
                'subtotal' => 0, // Will be updated after adding items
                'total_amount' => 0, // Will be updated after adding items
                'notes' => 'Order notes',
            ]);
            
            // Add order items
            $totalAmount = 0;
            $itemCount = rand(1, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 3);
                $totalPrice = $product->price * $quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                ]);
                
                $totalAmount += $totalPrice;
            }
            
            // Update order totals
            $order->update([
                'subtotal' => $totalAmount,
                'total_amount' => $totalAmount,
            ]);
        }

        // Create loyalty program
        $loyaltyProgram = LoyaltyProgram::create([
            'salon_id' => $salon->id,
            'name' => 'Premium Rewards Program',
            'description' => 'Earn points for every service and product purchase',
            'points_per_dollar' => 1,
            'points_required' => 100,
            'reward_value' => 10.00,
            'is_active' => true,
        ]);

        // Add loyalty points for customers
        foreach ($customers as $customer) {
            // Create some loyalty points for each customer
            for ($i = 0; $i < rand(3, 8); $i++) {
                LoyaltyPoint::create([
                    'user_id' => $customer->id,
                    'salon_id' => $salon->id,
                    'loyalty_program_id' => $loyaltyProgram->id,
                    'points' => rand(10, 50),
                    'source' => ['appointment', 'order'][rand(0, 1)],
                    'description' => 'Points earned for service',
                    'is_redeemed' => rand(0, 3) == 0, // 25% chance of being redeemed
                ]);
            }
            
            // Create some loyalty rewards for customers
            for ($i = 0; $i < rand(1, 3); $i++) {
                LoyaltyReward::create([
                    'user_id' => $customer->id,
                    'salon_id' => $salon->id,
                    'loyalty_program_id' => $loyaltyProgram->id,
                    'points_redeemed' => rand(50, 150),
                    'value' => rand(5, 20),
                    'type' => ['discount', 'free_service'][rand(0, 1)],
                    'description' => 'Reward for loyalty',
                    'is_used' => rand(0, 1),
                ]);
            }
        }

        // Create gift cards
        for ($i = 0; $i < 10; $i++) {
            $customer = rand(0, 2) == 0 ? $customers[array_rand($customers)] : null; // 1/3 chance of being assigned to a customer
            
            $initialAmount = rand(25, 100);
            $balance = rand(0, $initialAmount); // Some may be partially used
            
            GiftCard::create([
                'salon_id' => $salon->id,
                'customer_id' => $customer ? $customer->id : null,
                'code' => 'GC-' . strtoupper(Str::random(6)),
                'initial_amount' => $initialAmount,
                'balance' => $balance,
                'message' => 'Gift card for ' . ($customer ? $customer->name : 'a special someone'),
                'expires_at' => now()->addMonths(rand(3, 12)),
                'is_active' => true,
            ]);
        }

        // Create reviews
        for ($i = 0; $i < 15; $i++) {
            $customer = $customers[array_rand($customers)];
            $service = $services[array_rand($services)];
            $employeeUser = [null, $employee][rand(0, 1)]; // Randomly assign employee or leave null
            
            $ratings = [1, 2, 3, 4, 5];
            $rating = $ratings[array_rand($ratings)];
            
            $statuses = [0, 1]; // 0 = not approved, 1 = approved
            $isApproved = $statuses[array_rand($statuses)];
            
            Review::create([
                'salon_id' => $salon->id,
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'employee_id' => $employeeUser ? $employeeUser->id : null,
                'rating' => $rating,
                'review' => $this->generateReviewText($rating),
                'title' => $this->generateReviewTitle($rating),
                'is_approved' => $isApproved,
                'is_featured' => rand(0, 5) == 0, // 1/6 chance of being featured
            ]);
        }

        $this->command->info('Dummy data for salon created successfully!');
    }

    private function generateReviewText($rating)
    {
        $reviews = [
            1 => 'Very disappointed with the service. The stylist was rude and the haircut was terrible. Will not be returning.',
            2 => 'Not satisfied with the experience. Service was slow and the results were below expectations.',
            3 => 'Average experience. Some things were good, others could be improved. Might consider returning.',
            4 => 'Great service! The stylist was professional and I\'m happy with the results. Will definitely come back.',
            5 => 'Absolutely amazing! The best salon experience I\'ve ever had. The staff was wonderful and the results exceeded my expectations!'
        ];
        
        return $reviews[$rating];
    }
    
    private function generateReviewTitle($rating)
    {
        $titles = [
            1 => 'Terrible Experience',
            2 => 'Below Expectations',
            3 => 'Average Service',
            4 => 'Great Service',
            5 => 'Outstanding Experience'
        ];
        
        return $titles[$rating];
    }
}