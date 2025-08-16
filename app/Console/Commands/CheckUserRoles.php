<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserRoles extends Command
{
    protected $signature = 'check:user-roles {email?}';
    protected $description = 'Check user roles for debugging';

    public function handle()
    {
        $email = $this->argument('email') ?? 'sarah@beautyhavensalon.com';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return;
        }
        
        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Status: {$user->status}");
        $this->info("Salon ID: {$user->salon_id}");
        
        $roles = $user->roles;
        if ($roles->count() > 0) {
            $this->info("Roles: " . $roles->pluck('name')->implode(', '));
        } else {
            $this->warn("No roles assigned!");
        }
        
        $permissions = $user->getAllPermissions();
        if ($permissions->count() > 0) {
            $this->info("Permissions: " . $permissions->pluck('name')->implode(', '));
        } else {
            $this->warn("No permissions assigned!");
        }
    }
} 