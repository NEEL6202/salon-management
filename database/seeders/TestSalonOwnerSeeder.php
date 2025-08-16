<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Salon;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;

class TestSalonOwnerSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first subscription plan
        $subscriptionPlan = SubscriptionPlan::first();
        
        if (!$subscriptionPlan) {
            $this->command->error('No subscription plans found. Please run SubscriptionPlanSeeder first.');
            return;
        }

        // Create a test salon
        $salon = Salon::create([
            'name' => 'Beauty Haven Salon',
            'slug' => 'beauty-haven-salon',
            'description' => 'A premium beauty salon offering a wide range of services',
            'address' => '123 Main Street',
            'city' => 'Downtown',
            'state' => 'City',
            'country' => 'USA',
            'postal_code' => '12345',
            'phone' => '+1-555-0123',
            'email' => 'info@beautyhavensalon.com',
            'website' => 'https://beautyhavensalon.com',
            'business_hours' => json_encode([
                'monday' => ['09:00', '20:00'],
                'tuesday' => ['09:00', '20:00'],
                'wednesday' => ['09:00', '20:00'],
                'thursday' => ['09:00', '20:00'],
                'friday' => ['09:00', '20:00'],
                'saturday' => ['09:00', '18:00'],
                'sunday' => ['10:00', '16:00'],
            ]),
            'subscription_plan_id' => $subscriptionPlan->id,
            'status' => 'active',
        ]);

        // Create salon owner user
        $salonOwner = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@beautyhavensalon.com',
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0124',
            'gender' => 'female',
            'date_of_birth' => '1985-06-15',
            'address' => '123 Main Street, Downtown, City',
            'salon_id' => $salon->id,
            'status' => 'active',
            'created_by' => 1, // Super admin
        ]);

        // Assign salon owner role
        $salonOwner->assignRole('salon_owner');

        // Create a test manager
        $manager = User::create([
            'name' => 'Mike Wilson',
            'email' => 'mike@beautyhavensalon.com',
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0125',
            'gender' => 'male',
            'date_of_birth' => '1990-03-22',
            'address' => '456 Oak Avenue, Downtown, City',
            'salon_id' => $salon->id,
            'status' => 'active',
            'created_by' => $salonOwner->id,
        ]);

        $manager->assignRole('manager');

        // Create a test employee
        $employee = User::create([
            'name' => 'Emma Davis',
            'email' => 'emma@beautyhavensalon.com',
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0126',
            'gender' => 'female',
            'date_of_birth' => '1995-11-08',
            'address' => '789 Pine Street, Downtown, City',
            'salon_id' => $salon->id,
            'status' => 'active',
            'created_by' => $salonOwner->id,
        ]);

        $employee->assignRole('employee');

        $this->command->info('Test salon owner created successfully!');
        $this->command->info('Salon Owner Login: sarah@beautyhavensalon.com / password123');
        $this->command->info('Manager Login: mike@beautyhavensalon.com / password123');
        $this->command->info('Employee Login: emma@beautyhavensalon.com / password123');
    }
} 