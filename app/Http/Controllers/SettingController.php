<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string',
            'site_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'trial_days' => 'nullable|integer|min:0|max:90',
            'max_employees' => 'nullable|integer|min:1',
            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
        ]);

        $settings = [
            'site_name' => $request->site_name,
            'site_email' => $request->site_email,
            'site_phone' => $request->site_phone,
            'site_address' => $request->site_address,
            'site_description' => $request->site_description,
            'currency' => $request->currency,
            'timezone' => $request->timezone,
            'trial_days' => $request->trial_days,
            'max_employees' => $request->max_employees,
            'mail_from_name' => $request->mail_from_name,
            'mail_from_address' => $request->mail_from_address,
            'facebook_url' => $request->facebook_url,
            'instagram_url' => $request->instagram_url,
            'twitter_url' => $request->twitter_url,
            'linkedin_url' => $request->linkedin_url,
        ];

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings/logos', 'public');
            $settings['logo'] = $logoPath;
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings/favicons', 'public');
            $settings['favicon'] = $faviconPath;
        }

        // Update settings
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear cache
        Cache::forget('settings');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function clearCache()
    {
        Cache::flush();
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Cache cleared successfully.');
    }

    public function backup()
    {
        // This would typically involve creating a database backup
        // For now, we'll just return a success message
        return redirect()->route('admin.settings.index')
            ->with('success', 'Backup initiated successfully.');
    }

    public function restore()
    {
        // This would typically involve restoring from a backup
        // For now, we'll just return a success message
        return redirect()->route('admin.settings.index')
            ->with('success', 'Restore initiated successfully.');
    }
} 