<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Salon Management System',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Website name',
                'is_public' => true,
            ],
            [
                'key' => 'site_description',
                'value' => 'Complete salon management solution for beauty parlors and barber shops',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Website description',
                'is_public' => true,
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'file',
                'group' => 'general',
                'description' => 'Website logo',
                'is_public' => true,
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'type' => 'file',
                'group' => 'general',
                'description' => 'Website favicon',
                'is_public' => true,
            ],
            [
                'key' => 'default_currency',
                'value' => 'USD',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default currency',
                'is_public' => true,
            ],
            [
                'key' => 'default_timezone',
                'value' => 'UTC',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default timezone',
                'is_public' => true,
            ],

            // Email Settings
            [
                'key' => 'mail_from_name',
                'value' => 'Salon Management',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email sender name',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@salonmanagement.com',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email sender address',
                'is_public' => false,
            ],
            [
                'key' => 'email_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Enable email notifications',
                'is_public' => false,
            ],

            // SMS Settings
            [
                'key' => 'sms_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'sms',
                'description' => 'Enable SMS notifications',
                'is_public' => false,
            ],
            [
                'key' => 'sms_provider',
                'value' => 'twilio',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'SMS provider',
                'is_public' => false,
            ],

            // Payment Settings
            [
                'key' => 'stripe_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Enable Stripe payments',
                'is_public' => false,
            ],
            [
                'key' => 'stripe_publishable_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Stripe publishable key',
                'is_public' => false,
            ],
            [
                'key' => 'stripe_secret_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Stripe secret key',
                'is_public' => false,
            ],

            // Appointment Settings
            [
                'key' => 'appointment_advance_booking_days',
                'value' => '30',
                'type' => 'number',
                'group' => 'appointment',
                'description' => 'Days in advance customers can book',
                'is_public' => true,
            ],
            [
                'key' => 'appointment_cancellation_hours',
                'value' => '24',
                'type' => 'number',
                'group' => 'appointment',
                'description' => 'Hours before appointment for cancellation',
                'is_public' => true,
            ],
            [
                'key' => 'appointment_reminder_hours',
                'value' => '24',
                'type' => 'number',
                'group' => 'appointment',
                'description' => 'Hours before appointment for reminder',
                'is_public' => false,
            ],

            // Business Hours (Default)
            [
                'key' => 'default_business_hours',
                'value' => json_encode([
                    'monday' => ['09:00', '18:00'],
                    'tuesday' => ['09:00', '18:00'],
                    'wednesday' => ['09:00', '18:00'],
                    'thursday' => ['09:00', '18:00'],
                    'friday' => ['09:00', '18:00'],
                    'saturday' => ['09:00', '17:00'],
                    'sunday' => ['closed'],
                ]),
                'type' => 'json',
                'group' => 'business',
                'description' => 'Default business hours',
                'is_public' => true,
            ],

            // Terms and Conditions
            [
                'key' => 'terms_and_conditions',
                'value' => 'Terms and conditions content goes here...',
                'type' => 'string',
                'group' => 'legal',
                'description' => 'Terms and conditions',
                'is_public' => true,
            ],
            [
                'key' => 'privacy_policy',
                'value' => 'Privacy policy content goes here...',
                'type' => 'string',
                'group' => 'legal',
                'description' => 'Privacy policy',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
} 