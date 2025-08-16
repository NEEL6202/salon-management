<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUsers extends Command
{
    protected $signature = 'users:check';
    protected $description = 'Check all users and their roles';

    public function handle()
    {
        $users = User::with('roles')->get();
        
        $this->info('Users in the system:');
        $this->info('==================');
        
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $roles = $roles ?: 'No roles assigned';
            
            $this->line("ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Roles: {$roles}");
        }
        
        return 0;
    }
} 