<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Review;
use App\Models\Salon;
use App\Models\User;
use App\Models\Service;

class TestReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-review {salonId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the review functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $salonId = $this->argument('salonId');
        
        if (!$salonId) {
            $salonId = Salon::first()->id ?? null;
            if (!$salonId) {
                $this->error('No salon found. Please create a salon first.');
                return;
            }
        }
        
        $salon = Salon::find($salonId);
        if (!$salon) {
            $this->error('Salon not found.');
            return;
        }
        
        $this->info('Testing review for salon: ' . $salon->name);
        
        // Get a customer
        $customer = $salon->users()
            ->whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->first();
            
        if (!$customer) {
            $this->info('No customer found, creating a test customer...');
            $customer = $salon->users()->create([
                'name' => 'Test Customer',
                'email' => 'test@customer.com',
                'password' => bcrypt('password123'),
                'phone' => '1234567890',
                'status' => 'active',
            ]);
            $customer->assignRole('customer');
            $this->info('Created customer: ' . $customer->name);
        }
        
        $this->info('Using customer: ' . $customer->name);
        
        // Get a service
        $service = $salon->services()->first();
        if (!$service) {
            $this->info('No service found, creating a test service...');
            $service = $salon->services()->create([
                'name' => 'Test Service',
                'description' => 'A test service for reviews',
                'price' => 50.00,
                'duration' => 60,
                'is_active' => true,
            ]);
            $this->info('Created service: ' . $service->name);
        }
        
        $this->info('Using service: ' . $service->name);
        
        // Create a review
        $review = $salon->reviews()->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'rating' => 5,
            'title' => 'Excellent Service!',
            'review' => 'I had a wonderful experience at this salon. The service was excellent and the staff were very professional.',
            'is_approved' => true,
        ]);
        
        $this->info('Created review with rating: ' . $review->rating . ' stars');
        $this->info('Review title: ' . $review->title);
        
        $this->info('Test completed successfully!');
    }
}
