<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for small salons just getting started',
                'price' => 29.99,
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'max_employees' => 3,
                'max_services' => 20,
                'max_products' => 100,
                'is_active' => true,
                'is_popular' => false,
                'features' => [
                    'Basic appointment booking',
                    'Customer management',
                    'Basic reporting',
                    'Email notifications',
                    'Mobile responsive',
                ],
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Ideal for growing salons with multiple staff',
                'price' => 59.99,
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'max_employees' => 10,
                'max_services' => 50,
                'max_products' => 500,
                'is_active' => true,
                'is_popular' => true,
                'features' => [
                    'Everything in Starter',
                    'Advanced appointment management',
                    'Inventory management',
                    'Advanced reporting',
                    'SMS notifications',
                    'Online booking widget',
                    'Customer loyalty program',
                ],
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large salons and chains',
                'price' => 99.99,
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'max_employees' => 50,
                'max_services' => 200,
                'max_products' => 2000,
                'is_active' => true,
                'is_popular' => false,
                'features' => [
                    'Everything in Professional',
                    'Multi-location support',
                    'Advanced analytics',
                    'API access',
                    'Custom integrations',
                    'Priority support',
                    'White-label options',
                ],
            ],
            [
                'name' => 'Starter Yearly',
                'slug' => 'starter-yearly',
                'description' => 'Starter plan with yearly billing (2 months free)',
                'price' => 299.99,
                'billing_cycle' => 'yearly',
                'trial_days' => 14,
                'max_employees' => 3,
                'max_services' => 20,
                'max_products' => 100,
                'is_active' => true,
                'is_popular' => false,
                'features' => [
                    'Basic appointment booking',
                    'Customer management',
                    'Basic reporting',
                    'Email notifications',
                    'Mobile responsive',
                    '2 months free with yearly billing',
                ],
            ],
            [
                'name' => 'Professional Yearly',
                'slug' => 'professional-yearly',
                'description' => 'Professional plan with yearly billing (2 months free)',
                'price' => 599.99,
                'billing_cycle' => 'yearly',
                'trial_days' => 14,
                'max_employees' => 10,
                'max_services' => 50,
                'max_products' => 500,
                'is_active' => true,
                'is_popular' => false,
                'features' => [
                    'Everything in Starter',
                    'Advanced appointment management',
                    'Inventory management',
                    'Advanced reporting',
                    'SMS notifications',
                    'Online booking widget',
                    'Customer loyalty program',
                    '2 months free with yearly billing',
                ],
            ],
            [
                'name' => 'Enterprise Yearly',
                'slug' => 'enterprise-yearly',
                'description' => 'Enterprise plan with yearly billing (2 months free)',
                'price' => 999.99,
                'billing_cycle' => 'yearly',
                'trial_days' => 14,
                'max_employees' => 50,
                'max_services' => 200,
                'max_products' => 2000,
                'is_active' => true,
                'is_popular' => false,
                'features' => [
                    'Everything in Professional',
                    'Multi-location support',
                    'Advanced analytics',
                    'API access',
                    'Custom integrations',
                    'Priority support',
                    'White-label options',
                    '2 months free with yearly billing',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
} 