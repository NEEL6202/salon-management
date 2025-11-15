<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SubscriptionPlanSeeder::class,
            SuperAdminSeeder::class,
            SystemSettingsSeeder::class,
            TestSalonOwnerSeeder::class,
            SmsSettingsSeeder::class,
            SalonDummyDataSeeder::class,
        ]);
    }
}