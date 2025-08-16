<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlatformSetting;

class PlatformSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'platform_name',
                'value' => 'Salon Management Pro',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Platform Name',
                'description' => 'The name of your salon management platform',
                'is_public' => true,
            ],
            [
                'key' => 'platform_description',
                'value' => 'Complete salon management solution for beauty parlors, barber shops, and spa businesses',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Platform Description',
                'description' => 'Brief description of your platform',
                'is_public' => true,
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@salonmanagement.com',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Contact Email',
                'description' => 'Primary contact email for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1-555-0123',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Contact Phone',
                'description' => 'Primary contact phone number',
                'is_public' => true,
            ],

            // Appearance Settings
            [
                'key' => 'logo',
                'value' => 'images/logo.png',
                'type' => 'file',
                'group' => 'appearance',
                'label' => 'Platform Logo',
                'description' => 'Main logo for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'favicon',
                'value' => 'images/favicon.ico',
                'type' => 'file',
                'group' => 'appearance',
                'label' => 'Favicon',
                'description' => 'Website favicon',
                'is_public' => true,
            ],
            [
                'key' => 'primary_color',
                'value' => '#007bff',
                'type' => 'string',
                'group' => 'appearance',
                'label' => 'Primary Color',
                'description' => 'Primary brand color',
                'is_public' => true,
            ],
            [
                'key' => 'secondary_color',
                'value' => '#6c757d',
                'type' => 'string',
                'group' => 'appearance',
                'label' => 'Secondary Color',
                'description' => 'Secondary brand color',
                'is_public' => true,
            ],

            // Business Settings
            [
                'key' => 'currency',
                'value' => 'USD',
                'type' => 'string',
                'group' => 'business',
                'label' => 'Default Currency',
                'description' => 'Default currency for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'type' => 'string',
                'group' => 'business',
                'label' => 'Default Timezone',
                'description' => 'Default timezone for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'trial_days',
                'value' => '14',
                'type' => 'number',
                'group' => 'business',
                'label' => 'Free Trial Days',
                'description' => 'Number of free trial days for new salons',
                'is_public' => true,
            ],

            // Notification Settings
            [
                'key' => 'email_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Email Notifications',
                'description' => 'Enable email notifications',
                'is_public' => false,
            ],
            [
                'key' => 'sms_notifications',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'SMS Notifications',
                'description' => 'Enable SMS notifications',
                'is_public' => false,
            ],
            [
                'key' => 'push_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Push Notifications',
                'description' => 'Enable push notifications',
                'is_public' => false,
            ],

            // Payment Settings
            [
                'key' => 'stripe_public_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'label' => 'Stripe Public Key',
                'description' => 'Stripe public key for payments',
                'is_public' => false,
            ],
            [
                'key' => 'stripe_secret_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'label' => 'Stripe Secret Key',
                'description' => 'Stripe secret key for payments',
                'is_public' => false,
            ],
            [
                'key' => 'paypal_client_id',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'label' => 'PayPal Client ID',
                'description' => 'PayPal client ID for payments',
                'is_public' => false,
            ],
            [
                'key' => 'paypal_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'label' => 'PayPal Secret',
                'description' => 'PayPal secret for payments',
                'is_public' => false,
            ],

            // Terms and Conditions
            [
                'key' => 'terms_of_service',
                'value' => 'Terms of service content goes here...',
                'type' => 'text',
                'group' => 'legal',
                'label' => 'Terms of Service',
                'description' => 'Terms of service for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'privacy_policy',
                'value' => 'Privacy policy content goes here...',
                'type' => 'text',
                'group' => 'legal',
                'label' => 'Privacy Policy',
                'description' => 'Privacy policy for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'refund_policy',
                'value' => 'Refund policy content goes here...',
                'type' => 'text',
                'group' => 'legal',
                'label' => 'Refund Policy',
                'description' => 'Refund policy for the platform',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            PlatformSetting::create($setting);
        }

        $this->command->info('Platform settings seeded successfully!');
    }
} 