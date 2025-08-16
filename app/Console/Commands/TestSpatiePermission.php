<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestSpatiePermission extends Command
{
    protected $signature = 'test:spatie-permission';
    protected $description = 'Test Spatie Permission package functionality';

    public function handle()
    {
        $this->info('Testing Spatie Permission Package...');
        
        // Test roles
        $this->info("\n=== ROLES ===");
        $roles = Role::all();
        foreach ($roles as $role) {
            $this->info("Role: {$role->name}");
        }
        
        // Test permissions
        $this->info("\n=== PERMISSIONS ===");
        $permissions = Permission::all();
        $this->info("Total permissions: " . $permissions->count());
        
        // Test user with roles
        $this->info("\n=== USER ROLES TEST ===");
        $sarah = User::where('email', 'sarah@beautyhavensalon.com')->first();
        if ($sarah) {
            $this->info("User: {$sarah->name}");
            $this->info("Roles count: " . $sarah->roles->count());
            $this->info("Role names: " . $sarah->roles->pluck('name')->implode(', '));
            $this->info("Has salon_owner role: " . ($sarah->hasRole('salon_owner') ? 'Yes' : 'No'));
            
            // Test direct role check
            $role = Role::where('name', 'salon_owner')->first();
            if ($role) {
                $this->info("Role 'salon_owner' exists in database: Yes");
                $this->info("Users with this role: " . $role->users->count());
            } else {
                $this->info("Role 'salon_owner' exists in database: No");
            }
        } else {
            $this->error("Sarah user not found!");
        }
        
        return 0;
    }
} 