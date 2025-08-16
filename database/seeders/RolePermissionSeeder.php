<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Salon management
            'view_salons',
            'create_salons',
            'edit_salons',
            'delete_salons',
            
            // Service management
            'view_services',
            'create_services',
            'edit_services',
            'delete_services',
            
            // Product management
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            
            // Category management
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            
            // Appointment management
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'delete_appointments',
            'manage_appointments',
            
            // Order management
            'view_orders',
            'create_orders',
            'edit_orders',
            'delete_orders',
            
            // Inventory management
            'view_inventory',
            'manage_inventory',
            'view_reports',
            
            // Settings management
            'view_settings',
            'edit_settings',
            
            // Subscription management
            'view_subscriptions',
            'manage_subscriptions',
            
            // Messaging
            'send_messages',
            'view_messages',
            
            // Dashboard
            'view_dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $salonOwner = Role::create(['name' => 'salon_owner']);
        $manager = Role::create(['name' => 'manager']);
        $employee = Role::create(['name' => 'employee']);
        $customer = Role::create(['name' => 'customer']);

        // Assign permissions to super admin (all permissions)
        $superAdmin->givePermissionTo(Permission::all());

        // Assign permissions to salon owner
        $salonOwner->givePermissionTo([
            'view_users', 'create_users', 'edit_users',
            'view_services', 'create_services', 'edit_services', 'delete_services',
            'view_products', 'create_products', 'edit_products', 'delete_products',
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            'view_appointments', 'create_appointments', 'edit_appointments', 'delete_appointments', 'manage_appointments',
            'view_orders', 'create_orders', 'edit_orders', 'delete_orders',
            'view_inventory', 'manage_inventory', 'view_reports',
            'view_settings', 'edit_settings',
            'view_subscriptions',
            'send_messages', 'view_messages',
            'view_dashboard',
        ]);

        // Assign permissions to manager
        $manager->givePermissionTo([
            'view_users',
            'view_services', 'create_services', 'edit_services',
            'view_products', 'create_products', 'edit_products',
            'view_categories', 'create_categories', 'edit_categories',
            'view_appointments', 'create_appointments', 'edit_appointments', 'manage_appointments',
            'view_orders', 'create_orders', 'edit_orders',
            'view_inventory', 'manage_inventory',
            'view_settings',
            'send_messages', 'view_messages',
            'view_dashboard',
        ]);

        // Assign permissions to employee
        $employee->givePermissionTo([
            'view_services',
            'view_products',
            'view_categories',
            'view_appointments', 'create_appointments', 'edit_appointments',
            'view_orders', 'create_orders',
            'view_inventory',
            'send_messages', 'view_messages',
            'view_dashboard',
        ]);

        // Assign permissions to customer
        $customer->givePermissionTo([
            'view_services',
            'view_products',
            'view_categories',
            'view_appointments', 'create_appointments',
            'view_orders', 'create_orders',
            'send_messages', 'view_messages',
        ]);
    }
} 