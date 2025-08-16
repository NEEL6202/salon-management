<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AssignRole extends Command
{
    protected $signature = 'user:assign-role {email} {role}';
    protected $description = 'Assign a role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        $user->assignRole($role);
        
        $this->info("Role '{$role}' assigned to {$email} successfully.");
        
        // Show updated roles
        $roles = $user->roles->pluck('name')->implode(', ');
        $this->info("User roles: {$roles}");
        
        return 0;
    }
} 