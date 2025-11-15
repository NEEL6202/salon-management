<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoyaltyProgram;
use App\Models\Salon;
use App\Models\User;

class TestLoyaltyProgram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-loyalty-program {salonId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the loyalty program functionality';

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
        
        $this->info('Testing loyalty program for salon: ' . $salon->name);
        
        // Create a test loyalty program
        $loyaltyProgram = $salon->loyaltyPrograms()->create([
            'name' => 'Test Loyalty Program',
            'description' => 'A test loyalty program for testing purposes',
            'points_per_dollar' => 1,
            'points_required' => 100,
            'reward_value' => 5.00,
            'is_active' => true,
        ]);
        
        $this->info('Created loyalty program: ' . $loyaltyProgram->name);
        
        // Get a customer or create one
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
        
        // Award points
        $loyaltyPoint = $loyaltyProgram->loyaltyPoints()->create([
            'user_id' => $customer->id,
            'salon_id' => $salon->id,
            'points' => 50,
            'source' => 'test',
            'description' => 'Test points',
        ]);
        
        $this->info('Awarded 50 points to customer.');
        
        // Check total points
        $totalPoints = $customer->loyaltyPoints()
            ->where('loyalty_program_id', $loyaltyProgram->id)
            ->where('is_redeemed', false)
            ->sum('points');
            
        $this->info('Customer total points: ' . $totalPoints);
        
        $this->info('Test completed successfully!');
    }
}
