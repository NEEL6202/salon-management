<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\PlatformSetting;

class SmsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System Settings for SMS
        $systemSettings = [
            [
                'key' => 'twilio_sid',
                'value' => '',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'Twilio Account SID',
                'is_public' => false,
            ],
            [
                'key' => 'twilio_token',
                'value' => '',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'Twilio Auth Token',
                'is_public' => false,
            ],
            [
                'key' => 'twilio_from_number',
                'value' => '',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'Twilio phone number',
                'is_public' => false,
            ],
        ];

        foreach ($systemSettings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // Platform Settings for SMS
        $platformSettings = [
            [
                'key' => 'twilio_sid',
                'value' => '',
                'type' => 'string',
                'group' => 'sms',
                'label' => 'Twilio SID',
                'description' => 'Twilio Account SID',
                'is_public' => false,
            ],
            [
                'key' => 'twilio_token',
                'value' => '',
                'type' => 'string',
                'group' => 'sms',
                'label' => 'Twilio Token',
                'description' => 'Twilio Auth Token',
                'is_public' => false,
            ],
            [
                'key' => 'twilio_from_number',
                'value' => '',
                'type' => 'string',
                'group' => 'sms',
                'label' => 'Twilio From Number',
                'description' => 'Twilio phone number to send SMS from',
                'is_public' => false,
            ],
        ];

        foreach ($platformSettings as $setting) {
            PlatformSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info('SMS settings seeded successfully!');
    }
}
