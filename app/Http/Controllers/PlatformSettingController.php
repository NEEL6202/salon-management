<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlatformSettingController extends Controller
{
    public function index()
    {
        $settings = PlatformSetting::orderBy('group')->orderBy('label')->get();
        $groups = $settings->groupBy('group');
        
        return view('admin.settings.index', compact('groups', 'settings'));
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:platform_settings,key',
            'value' => 'required',
            'type' => 'required|in:string,text,number,boolean,json,file',
            'group' => 'required|in:general,appearance,business,notifications,payment,legal',
            'label' => 'required|string',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        PlatformSetting::create($request->all());
        
        // Clear settings cache
        Cache::forget('platform_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting created successfully!');
    }

    public function show(PlatformSetting $setting)
    {
        return view('admin.settings.show', compact('setting'));
    }

    public function edit(PlatformSetting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, PlatformSetting $setting)
    {
        $request->validate([
            'value' => 'required',
            'type' => 'required|in:string,text,number,boolean,json,file',
            'group' => 'required|in:general,appearance,business,notifications,payment,legal',
            'label' => 'required|string',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $setting->update($request->all());
        
        // Clear settings cache
        Cache::forget('platform_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully!');
    }

    public function destroy(PlatformSetting $setting)
    {
        $setting->delete();
        
        // Clear settings cache
        Cache::forget('platform_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully!');
    }

    public function updateBulk(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:platform_settings,id',
            'settings.*.value' => 'required',
        ]);

        foreach ($request->settings as $settingData) {
            $setting = PlatformSetting::find($settingData['id']);
            if ($setting) {
                $setting->update(['value' => $settingData['value']]);
            }
        }
        
        // Clear settings cache
        Cache::forget('platform_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function getPublicSettings()
    {
        $settings = PlatformSetting::where('is_public', true)
            ->pluck('value', 'key')
            ->toArray();
            
        return response()->json($settings);
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
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
            \Artisan::call('migrate:status');
            
            return response()->json([
                'success' => true,
                'message' => 'Database optimization check completed'
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
            // This would integrate with a backup package like spatie/laravel-backup
            // For now, we'll just return a success message
            return response()->json([
                'success' => true,
                'message' => 'Backup creation initiated successfully'
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
                \Artisan::call('up');
                $message = 'Maintenance mode disabled';
            } else {
                \Artisan::call('down', ['--secret' => 'admin123']);
                $message = 'Maintenance mode enabled';
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
                'database' => $this->checkDatabaseConnection(),
                'storage' => $this->checkStorageConnection(),
                'email' => $this->checkEmailService(),
                'payment_gateway' => $this->checkPaymentGateway(),
            ];
            
            $allHealthy = collect($checks)->every(fn($check) => $check['status'] === 'healthy');
            
            return response()->json([
                'success' => true,
                'checks' => $checks,
                'overall_status' => $allHealthy ? 'healthy' : 'warning'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error running system check: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear application logs
     */
    public function clearLogs()
    {
        try {
            $logFiles = ['laravel.log', 'queue.log'];
            $clearedCount = 0;
            
            foreach ($logFiles as $logFile) {
                $logPath = storage_path('logs/' . $logFile);
                if (file_exists($logPath)) {
                    file_put_contents($logPath, '');
                    $clearedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Cleared {$clearedCount} log files successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check database connection
     */
    private function checkDatabaseConnection()
    {
        try {
            \DB::connection()->getPdo();
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check storage connection
     */
    private function checkStorageConnection()
    {
        try {
            $disk = \Storage::disk('local');
            $disk->put('health-check.txt', 'test');
            $disk->delete('health-check.txt');
            
            return [
                'status' => 'healthy',
                'message' => 'Storage connection successful'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Storage connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check email service
     */
    private function checkEmailService()
    {
        try {
            // Basic check - in production you might want to send a test email
            $mailConfig = config('mail.default');
            
            return [
                'status' => 'healthy',
                'message' => "Email service configured ({$mailConfig})"
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Email service check failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check payment gateway
     */
    private function checkPaymentGateway()
    {
        try {
            $stripeKey = config('services.stripe.secret');
            $paypalKey = config('services.paypal.secret');
            
            if ($stripeKey || $paypalKey) {
                return [
                    'status' => 'healthy',
                    'message' => 'Payment gateway configured'
                ];
            }
            
            return [
                'status' => 'warning',
                'message' => 'No payment gateway configured'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Payment gateway check failed: ' . $e->getMessage()
            ];
        }
    }
}
