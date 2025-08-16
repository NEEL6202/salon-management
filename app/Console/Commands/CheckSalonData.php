<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Salon;

class CheckSalonData extends Command
{
    protected $signature = 'check:salon-data';
    protected $description = 'Check salon data for debugging';

    public function handle()
    {
        $this->info('Checking salon data...');
        
        // Check users with salons
        $users = User::with('salon')->get();
        
        $this->info('Users and their salons:');
        $this->table(
            ['ID', 'Name', 'Email', 'Salon ID', 'Salon Name', 'Salon Status'],
            $users->map(function($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->salon_id ?? 'N/A',
                    $user->salon->name ?? 'N/A',
                    $user->salon->status ?? 'N/A'
                ];
            })
        );
        
        // Check salons
        $salons = Salon::with('users')->get();
        
        $this->info('Salons and their users:');
        $this->table(
            ['ID', 'Name', 'Email', 'Status', 'User Count'],
            $salons->map(function($salon) {
                return [
                    $salon->id,
                    $salon->name,
                    $salon->email,
                    $salon->status,
                    $salon->users->count()
                ];
            })
        );
        
        return 0;
    }
} 