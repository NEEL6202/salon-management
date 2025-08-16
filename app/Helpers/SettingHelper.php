<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingHelper
{
    /**
     * Get a setting value
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting.{$key}");
        return $setting;
    }

    /**
     * Get multiple settings
     */
    public static function getMultiple($keys, $defaults = [])
    {
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = self::get($key, $defaults[$key] ?? null);
        }
        return $settings;
    }

    /**
     * Set multiple settings
     */
    public static function setMultiple($settings)
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::flush();
    }
}

// Global helper function
if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Helpers\SettingHelper::get($key, $default);
    }
}

