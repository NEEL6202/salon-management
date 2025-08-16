<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\SettingHelper;
use Illuminate\Http\Request;
use App\Models\PlatformSetting;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class AdminSettingsController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at the route level
    }

    /**
     * Display the main settings page
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        $systemInfo = $this->getSystemInfo();
        
        return view('admin.settings.index', compact('settings', 'systemInfo'));
    }

    /**
     * Display general settings
     */
    public function general()
    {
        $settings = $this->getGeneralSettings();
        
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Display email settings
     */
    public function email()
    {
        $settings = $this->getEmailSettings();
        
        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Display payment settings
     */
    public function payment()
    {
        $settings = $this->getPaymentSettings();
        
        return view('admin.settings.payment', compact('settings'));
    }

    /**
     * Display notification settings
     */
    public function notifications()
    {
        $settings = $this->getNotificationSettings();
        
        return view('admin.settings.notifications', compact('settings'));
    }

    /**
     * Display security settings
     */
    public function security()
    {
        $settings = $this->getSecuritySettings();
        
        return view('admin.settings.security', compact('settings'));
    }

    /**
     * Display appearance settings
     */
    public function appearance()
    {
        $settings = $this->getAppearanceSettings();
        
        return view('admin.settings.appearance', compact('settings'));
    }

    /**
     * Display integration settings
     */
    public function integrations()
    {
        $settings = $this->getIntegrationSettings();
        
        return view('admin.settings.integrations', compact('settings'));
    }

    /**
     * Display backup settings
     */
    public function backup()
    {
        $backups = $this->getBackupInfo();
        $settings = $this->getBackupSettings();
        
        return view('admin.settings.backup', compact('backups', 'settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validator = $this->validateSettings($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->updateSettings($request->all());
        
        // Clear cache
        Cache::flush();
        
        return redirect()->back()
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Update specific setting group
     */
    public function updateGroup(Request $request, $group)
    {
        $validator = $this->validateGroupSettings($request, $group);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->updateGroupSettings($request->all(), $group);
        
        // Clear cache
        Cache::flush();
        
        return redirect()->back()
            ->with('success', ucfirst($group) . ' settings updated successfully.');
    }

    /**
     * Clear system cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize database
     */
    public function optimizeDatabase()
    {
        try {
            // Analyze tables
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                DB::statement("ANALYZE TABLE {$tableName}");
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Database optimized successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error optimizing database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create system backup
     */
    public function createBackup()
    {
        try {
            $backupPath = storage_path('backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupPath . '/' . $filename;

            // Database backup
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                config('database.connections.mysql.host'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $filepath
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception('Database backup failed');
            }

            // Create backup record
            $this->createBackupRecord($filename, $filepath);

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully.',
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenanceMode()
    {
        try {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
                $message = 'Maintenance mode disabled.';
            } else {
                Artisan::call('down', ['--secret' => 'admin123']);
                $message = 'Maintenance mode enabled.';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'maintenance_mode' => app()->isDownForMaintenance()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run system health check
     */
    public function runSystemCheck()
    {
        try {
            $checks = [
                'database' => $this->checkDatabase(),
                'storage' => $this->checkStorage(),
                'permissions' => $this->checkPermissions(),
                'extensions' => $this->checkExtensions(),
                'services' => $this->checkServices(),
            ];

            $overallStatus = collect($checks)->every(fn($check) => $check['status'] === 'healthy') ? 'healthy' : 'warning';

            return response()->json([
                'success' => true,
                'checks' => $checks,
                'overall_status' => $overallStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error running system check: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear system logs
     */
    public function clearLogs()
    {
        try {
            $logFiles = [
                storage_path('logs/laravel.log'),
                storage_path('logs/laravel-*.log'),
            ];

            foreach ($logFiles as $logFile) {
                if (File::exists($logFile)) {
                    File::delete($logFile);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'System logs cleared successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all settings
     */
    private function getAllSettings()
    {
        return [
            'general' => $this->getGeneralSettings(),
            'email' => $this->getEmailSettings(),
            'payment' => $this->getPaymentSettings(),
            'notifications' => $this->getNotificationSettings(),
            'security' => $this->getSecuritySettings(),
            'appearance' => $this->getAppearanceSettings(),
            'integrations' => $this->getIntegrationSettings(),
            'backup' => $this->getBackupSettings(),
        ];
    }

    /**
     * Get general settings
     */
    private function getGeneralSettings()
    {
        return [
            'site_name' => SettingHelper::get('site_name', 'Salon Management System'),
            'site_description' => SettingHelper::get('site_description', 'Professional salon management platform'),
            'site_keywords' => SettingHelper::get('site_keywords', 'salon, management, booking, appointments'),
            'site_url' => SettingHelper::get('site_url', config('app.url')),
            'timezone' => SettingHelper::get('timezone', config('app.timezone')),
            'date_format' => SettingHelper::get('date_format', 'Y-m-d'),
            'time_format' => SettingHelper::get('time_format', 'H:i'),
            'currency' => SettingHelper::get('currency', 'USD'),
            'currency_symbol' => SettingHelper::get('currency_symbol', '$'),
            'language' => SettingHelper::get('language', 'en'),
            'maintenance_mode' => SettingHelper::get('maintenance_mode', false),
        ];
    }

    /**
     * Get email settings
     */
    private function getEmailSettings()
    {
        return [
            'mail_driver' => SettingHelper::get('mail_driver', config('mail.default')),
            'mail_host' => SettingHelper::get('mail_host', config('mail.mailers.smtp.host')),
            'mail_port' => SettingHelper::get('mail_port', config('mail.mailers.smtp.port')),
            'mail_username' => SettingHelper::get('mail_username', config('mail.mailers.smtp.username')),
            'mail_password' => SettingHelper::get('mail_password', config('mail.mailers.smtp.password')),
            'mail_encryption' => SettingHelper::get('mail_encryption', config('mail.mailers.smtp.encryption')),
            'mail_from_address' => SettingHelper::get('mail_from_address', config('mail.from.address')),
            'mail_from_name' => SettingHelper::get('mail_from_name', config('mail.from.name')),
        ];
    }

    /**
     * Get payment settings
     */
    private function getPaymentSettings()
    {
        return [
            'stripe_public_key' => SettingHelper::get('stripe_public_key', ''),
            'stripe_secret_key' => SettingHelper::get('stripe_secret_key', ''),
            'stripe_webhook_secret' => SettingHelper::get('stripe_webhook_secret', ''),
            'paypal_client_id' => SettingHelper::get('paypal_client_id', ''),
            'paypal_secret' => SettingHelper::get('paypal_secret', ''),
            'paypal_mode' => SettingHelper::get('paypal_mode', 'sandbox'),
            'payment_currency' => SettingHelper::get('payment_currency', 'USD'),
            'payment_methods' => SettingHelper::get('payment_methods', ['stripe', 'paypal']),
        ];
    }

    /**
     * Get notification settings
     */
    private function getNotificationSettings()
    {
        return [
            'email_notifications' => SettingHelper::get('email_notifications', true),
            'sms_notifications' => SettingHelper::get('sms_notifications', false),
            'push_notifications' => SettingHelper::get('push_notifications', false),
            'appointment_reminders' => SettingHelper::get('appointment_reminders', true),
            'booking_confirmation' => SettingHelper::get('booking_confirmation', true),
            'payment_confirmation' => SettingHelper::get('payment_confirmation', true),
            'marketing_emails' => SettingHelper::get('marketing_emails', false),
        ];
    }

    /**
     * Get security settings
     */
    private function getSecuritySettings()
    {
        return [
            'password_min_length' => SettingHelper::get('password_min_length', 8),
            'password_require_special' => SettingHelper::get('password_require_special', true),
            'password_require_numbers' => SettingHelper::get('password_require_numbers', true),
            'password_require_uppercase' => SettingHelper::get('password_require_uppercase', true),
            'session_lifetime' => SettingHelper::get('session_lifetime', 120),
            'max_login_attempts' => SettingHelper::get('max_login_attempts', 5),
            'lockout_duration' => SettingHelper::get('lockout_duration', 15),
            'two_factor_auth' => SettingHelper::get('two_factor_auth', false),
            'recaptcha_enabled' => SettingHelper::get('recaptcha_enabled', false),
            'recaptcha_site_key' => SettingHelper::get('recaptcha_site_key', ''),
            'recaptcha_secret_key' => SettingHelper::get('recaptcha_secret_key', ''),
        ];
    }

    /**
     * Get appearance settings
     */
    private function getAppearanceSettings()
    {
        return [
            'theme' => SettingHelper::get('theme', 'default'),
            'primary_color' => SettingHelper::get('primary_color', '#3B82F6'),
            'secondary_color' => SettingHelper::get('secondary_color', '#6B7280'),
            'logo' => SettingHelper::get('logo', ''),
            'favicon' => SettingHelper::get('favicon', ''),
            'custom_css' => SettingHelper::get('custom_css', ''),
            'custom_js' => SettingHelper::get('custom_js', ''),
            'footer_text' => SettingHelper::get('footer_text', '© 2024 Salon Management System. All rights reserved.'),
        ];
    }

    /**
     * Get integration settings
     */
    private function getIntegrationSettings()
    {
        return [
            'google_analytics_id' => SettingHelper::get('google_analytics_id', ''),
            'facebook_pixel_id' => SettingHelper::get('facebook_pixel_id', ''),
            'google_maps_api_key' => SettingHelper::get('google_maps_api_key', ''),
            'social_media_links' => SettingHelper::get('social_media_links', []),
            'third_party_scripts' => SettingHelper::get('third_party_scripts', ''),
        ];
    }

    /**
     * Get backup settings
     */
    private function getBackupSettings()
    {
        return [
            'auto_backup' => SettingHelper::get('auto_backup', false),
            'backup_frequency' => SettingHelper::get('backup_frequency', 'daily'),
            'backup_retention' => SettingHelper::get('backup_retention', 30),
            'backup_notifications' => SettingHelper::get('backup_notifications', true),
            'backup_storage' => SettingHelper::get('backup_storage', 'local'),
        ];
    }

    /**
     * Get system information
     */
    private function getSystemInfo()
    {
        return [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_driver' => config('database.default'),
            'database_version' => $this->getDatabaseVersion(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'disk_free_space' => $this->formatBytes(disk_free_space('/')),
            'disk_total_space' => $this->formatBytes(disk_total_space('/')),
        ];
    }

    /**
     * Get backup information
     */
    private function getBackupInfo()
    {
        $backupPath = storage_path('backups');
        $backups = [];

        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        return $backups;
    }

    /**
     * Validate settings
     */
    private function validateSettings(Request $request)
    {
        return validator($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_url' => 'required|url',
            'timezone' => 'required|string',
            'currency' => 'required|string|size:3',
            'mail_host' => 'required_if:mail_driver,smtp|string',
            'mail_port' => 'required_if:mail_driver,smtp|integer|min:1|max:65535',
            'mail_username' => 'required_if:mail_driver,smtp|string',
            'mail_password' => 'required_if:mail_driver,smtp|string',
        ]);
    }

    /**
     * Validate group settings
     */
    private function validateGroupSettings(Request $request, $group)
    {
        $rules = [];

        switch ($group) {
            case 'general':
                $rules = [
                    'site_name' => 'required|string|max:255',
                    'site_description' => 'nullable|string|max:500',
                    'site_url' => 'required|url',
                    'timezone' => 'required|string',
                    'currency' => 'required|string|size:3',
                ];
                break;
            case 'email':
                $rules = [
                    'mail_driver' => 'required|string',
                    'mail_host' => 'required_if:mail_driver,smtp|string',
                    'mail_port' => 'required_if:mail_driver,smtp|integer|min:1|max:65535',
                    'mail_username' => 'required_if:mail_driver,smtp|string',
                    'mail_password' => 'required_if:mail_driver,smtp|string',
                ];
                break;
            case 'payment':
                $rules = [
                    'stripe_public_key' => 'nullable|string',
                    'stripe_secret_key' => 'nullable|string',
                    'paypal_client_id' => 'nullable|string',
                    'paypal_secret' => 'nullable|string',
                ];
                break;
        }

        return validator($request->all(), $rules);
    }

    /**
     * Update settings
     */
    private function updateSettings(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key !== '_token' && $key !== '_method') {
                SettingHelper::set($key, $value);
            }
        }
    }

    /**
     * Update group settings
     */
    private function updateGroupSettings(array $data, $group)
    {
        $groupKeys = $this->getGroupKeys($group);
        
        foreach ($data as $key => $value) {
            if (in_array($key, $groupKeys) && $key !== '_token' && $key !== '_method') {
                SettingHelper::set($key, $value);
            }
        }
    }

    /**
     * Get group keys
     */
    private function getGroupKeys($group)
    {
        $groups = [
            'general' => ['site_name', 'site_description', 'site_url', 'timezone', 'currency'],
            'email' => ['mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password'],
            'payment' => ['stripe_public_key', 'stripe_secret_key', 'paypal_client_id', 'paypal_secret'],
            'notifications' => ['email_notifications', 'sms_notifications', 'push_notifications'],
            'security' => ['password_min_length', 'max_login_attempts', 'two_factor_auth'],
            'appearance' => ['theme', 'primary_color', 'secondary_color', 'logo'],
            'integrations' => ['google_analytics_id', 'facebook_pixel_id', 'google_maps_api_key'],
            'backup' => ['auto_backup', 'backup_frequency', 'backup_retention'],
        ];

        return $groups[$group] ?? [];
    }

    /**
     * Create backup record
     */
    private function createBackupRecord($filename, $filepath)
    {
        // You can implement a backup model to track backups
        Log::info('Backup created: ' . $filename);
    }

    /**
     * Check database health
     */
    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()];
        }
    }

    /**
     * Check storage health
     */
    private function checkStorage()
    {
        $freeSpace = disk_free_space('/');
        $totalSpace = disk_total_space('/');
        $usagePercent = (($totalSpace - $freeSpace) / $totalSpace) * 100;

        if ($usagePercent > 90) {
            return ['status' => 'error', 'message' => 'Storage usage critical: ' . round($usagePercent, 1) . '%'];
        } elseif ($usagePercent > 80) {
            return ['status' => 'warning', 'message' => 'Storage usage high: ' . round($usagePercent, 1) . '%'];
        }

        return ['status' => 'healthy', 'message' => 'Storage usage: ' . round($usagePercent, 1) . '%'];
    }

    /**
     * Check permissions
     */
    private function checkPermissions()
    {
        $paths = [
            storage_path() => '0755',
            storage_path('logs') => '0755',
            storage_path('app') => '0755',
            public_path('storage') => '0755',
        ];

        $issues = [];
        foreach ($paths as $path => $required) {
            if (!is_writable($path)) {
                $issues[] = $path . ' is not writable';
            }
        }

        if (empty($issues)) {
            return ['status' => 'healthy', 'message' => 'All required paths are writable'];
        }

        return ['status' => 'warning', 'message' => 'Permission issues: ' . implode(', ', $issues)];
    }

    /**
     * Check extensions
     */
    private function checkExtensions()
    {
        $required = ['pdo', 'pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json'];
        $missing = [];

        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }

        if (empty($missing)) {
            return ['status' => 'healthy', 'message' => 'All required extensions are loaded'];
        }

        return ['status' => 'error', 'message' => 'Missing extensions: ' . implode(', ', $missing)];
    }

    /**
     * Check services
     */
    private function checkServices()
    {
        $services = [
            'cache' => Cache::driver()->get('test') !== null || Cache::driver()->put('test', 'test', 1),
            'queue' => true, // Add queue health check if needed
        ];

        $issues = [];
        foreach ($services as $service => $status) {
            if (!$status) {
                $issues[] = $service . ' service is not responding';
            }
        }

        if (empty($issues)) {
            return ['status' => 'healthy', 'message' => 'All services are responding'];
        }

        return ['status' => 'warning', 'message' => 'Service issues: ' . implode(', ', $issues)];
    }

    /**
     * Get database version
     */
    private function getDatabaseVersion()
    {
        try {
            $result = DB::select('SELECT VERSION() as version');
            return $result[0]->version ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
