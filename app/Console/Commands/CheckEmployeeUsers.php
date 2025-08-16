<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckEmployeeUsers extends Command
{
    protected $signature = 'users:check-employees';
    protected $description = 'Check for users with employee role';

    public function handle()
    {
        $this->info('Checking for users with employee role...');
        
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'employee');
        })->get();
        
        $this->info("Users with employee role: " . $users->count());
        
        if ($users->count() > 0) {
            foreach ($users as $user) {
                $this->line("ID: {$user->id} | Name: {$user->name} | Email: {$user->email}");
            }
        } else {
            $this->warn("No users found with employee role!");
        }
        
        // Check all users and their roles
        $this->info("\nAll users and their roles:");
        $allUsers = User::with('roles')->get();
        foreach ($allUsers as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $roles = $roles ?: 'No roles assigned';
            $this->line("ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Roles: {$roles}");
        }
        
        return 0;
    }
} 