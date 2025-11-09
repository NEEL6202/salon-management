<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SalonSettingsController extends Controller
{
    public function index()
    {
        $salon = Auth::user()->salon;
        
        if (!$salon) {
            return redirect()->route('salon.profile')->with('error', 'Please complete your salon profile first.');
        }

        $settings = Setting::where('salon_id', $salon->id)->pluck('value', 'key');

        return view('salon.settings.index', compact('salon', 'settings'));
    }

    public function update(Request $request)
    {
        $salon = Auth::user()->salon;

        $request->validate([
            'business_hours' => 'nullable|array',
            'appointment_duration' => 'nullable|integer|min:15|max:480',
            'booking_buffer_time' => 'nullable|integer|min:0|max:60',
            'advance_booking_days' => 'nullable|integer|min:1|max:365',
            'cancellation_policy' => 'nullable|string|max:1000',
            'auto_confirm_appointments' => 'nullable|boolean',
            'send_appointment_reminders' => 'nullable|boolean',
            'reminder_hours_before' => 'nullable|integer|min:1|max:72',
            'send_sms_notifications' => 'nullable|boolean',
            'send_email_notifications' => 'nullable|boolean',
            'allow_online_booking' => 'nullable|boolean',
            'require_prepayment' => 'nullable|boolean',
            'prepayment_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $settings = $request->except('_token', '_method');

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['salon_id' => $salon->id, 'key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        Cache::forget("salon_settings_{$salon->id}");

        return redirect()->route('salon.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function businessHours()
    {
        $salon = Auth::user()->salon;
        
        $hours = Setting::where('salon_id', $salon->id)
            ->where('key', 'business_hours')
            ->first();

        $businessHours = $hours ? json_decode($hours->value, true) : [
            'monday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
            'tuesday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
            'wednesday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
            'thursday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
            'friday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
            'saturday' => ['open' => '10:00', 'close' => '16:00', 'closed' => false],
            'sunday' => ['open' => '00:00', 'close' => '00:00', 'closed' => true],
        ];

        return view('salon.settings.business-hours', compact('salon', 'businessHours'));
    }

    public function updateBusinessHours(Request $request)
    {
        $salon = Auth::user()->salon;

        $request->validate([
            'hours' => 'required|array',
            'hours.*.open' => 'required_if:hours.*.closed,false',
            'hours.*.close' => 'required_if:hours.*.closed,false',
        ]);

        Setting::updateOrCreate(
            ['salon_id' => $salon->id, 'key' => 'business_hours'],
            ['value' => json_encode($request->hours)]
        );

        Cache::forget("salon_settings_{$salon->id}");

        return redirect()->route('salon.settings.business-hours')
            ->with('success', 'Business hours updated successfully.');
    }
}
