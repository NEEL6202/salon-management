<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GiftCard;
use App\Models\Salon;
use App\Models\User;

class TestGiftCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-gift-card {salonId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the gift card functionality';

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
        
        $this->info('Testing gift card for salon: ' . $salon->name);
        
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
        
        // Create a gift card
        $giftCard = $salon->giftCards()->create([
            'customer_id' => $customer->id,
            'initial_amount' => 50.00,
            'balance' => 50.00,
            'message' => 'Test gift card',
        ]);
        
        $this->info('Created gift card with code: ' . $giftCard->code);
        $this->info('Gift card balance: $' . number_format($giftCard->balance, 2));
        
        $this->info('Test completed successfully!');
    }
}
