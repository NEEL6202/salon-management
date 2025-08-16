<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestMiddleware extends Command
{
    protected $signature = 'test:middleware {email}';
    protected $description = 'Test middleware functionality';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User not found!");
            return;
        }
        
        // Simulate authentication
        Auth::login($user);
        
        $this->info("Testing user: {$user->name}");
        $this->info("Roles: " . $user->roles->pluck('name')->implode(', '));
        
        // Test role checks
        $this->info("Has role 'salon_owner': " . ($user->hasRole('salon_owner') ? 'YES' : 'NO'));
        $this->info("Has role 'super_admin': " . ($user->hasRole('super_admin') ? 'YES' : 'NO'));
        $this->info("Has any role ['salon_owner']: " . ($user->hasAnyRole(['salon_owner']) ? 'YES' : 'NO'));
        $this->info("Has any role ['manager', 'employee']: " . ($user->hasAnyRole(['manager', 'employee']) ? 'YES' : 'NO'));
        
        Auth::logout();
    }
} 