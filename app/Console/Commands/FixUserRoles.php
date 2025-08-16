<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixUserRoles extends Command
{
    protected $signature = 'fix:user-roles';
    protected $description = 'Fix user roles and permissions for all test users';

    public function handle()
    {
        $this->info('Fixing user roles and permissions...');

        // Ensure roles exist
        $roles = ['super_admin', 'salon_owner', 'manager', 'employee', 'customer'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Fix admin user
        $admin = User::where('email', 'admin@salonmanagement.com')->first();
        if ($admin) {
            $admin->assignRole('super_admin');
            $this->info("✅ Admin user roles fixed");
        } else {
            $this->error("❌ Admin user not found");
        }

        // Fix salon owner
        $salonOwner = User::where('email', 'sarah@beautyhavensalon.com')->first();
        if ($salonOwner) {
            $salonOwner->assignRole('salon_owner');
            $this->info("✅ Salon owner roles fixed");
        } else {
            $this->error("❌ Salon owner not found");
        }

        // Fix manager
        $manager = User::where('email', 'mike@beautyhavensalon.com')->first();
        if ($manager) {
            $manager->assignRole('manager');
            $this->info("✅ Manager roles fixed");
        } else {
            $this->error("❌ Manager not found");
        }

        // Fix employee
        $employee = User::where('email', 'emma@beautyhavensalon.com')->first();
        if ($employee) {
            $employee->assignRole('employee');
            $this->info("✅ Employee roles fixed");
        } else {
            $this->error("❌ Employee not found");
        }

        // Create test customer if not exists
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Test Customer',
                'password' => bcrypt('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole('customer');
        $this->info("✅ Customer user created/fixed");

        $this->info('User roles fixed successfully!');
        
        // Display all users and their roles
        $this->info("\nCurrent users and their roles:");
        $users = User::with('roles')->get();
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $this->line("{$user->name} ({$user->email}): {$roles}");
        }
    }
}
