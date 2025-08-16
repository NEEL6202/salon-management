<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestRoleMiddleware extends Command
{
    protected $signature = 'test:role-middleware {email}';
    protected $description = 'Test role middleware functionality for employees';

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
        $this->info("Email: {$user->email}");
        $this->info("Status: {$user->status}");
        $this->info("Salon ID: {$user->salon_id}");
        $this->info("Roles: " . $user->roles->pluck('name')->implode(', '));
        
        // Test role checks
        $this->info("Has role 'employee': " . ($user->hasRole('employee') ? 'YES' : 'NO'));
        $this->info("Has role 'manager': " . ($user->hasRole('manager') ? 'YES' : 'NO'));
        $this->info("Has role 'salon_owner': " . ($user->hasRole('salon_owner') ? 'YES' : 'NO'));
        $this->info("Has role 'super_admin': " . ($user->hasRole('super_admin') ? 'YES' : 'NO'));
        
        // Test multiple role checks
        $this->info("Has any role ['employee', 'manager']: " . ($user->hasAnyRole(['employee', 'manager']) ? 'YES' : 'NO'));
        $this->info("Has all roles ['employee', 'manager']: " . ($user->hasAllRoles(['employee', 'manager']) ? 'YES' : 'NO'));
        
        // Test if user can access employee routes
        $this->info("Can access employee dashboard: " . ($user->hasAnyRole(['employee', 'manager']) ? 'YES' : 'NO'));
        
        Auth::logout();
        
        $this->info("Test completed!");
    }
} 