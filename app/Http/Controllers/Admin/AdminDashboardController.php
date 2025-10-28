<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at the route level
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Basic statistics
        $totalSalons = Salon::count();
        $totalUsers = User::count();
        $activeSubscriptions = Salon::where('status', 'active')->count();
        
        // Monthly statistics
        $newSalonsThisMonth = Salon::whereMonth('created_at', now()->month)->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        
        // Revenue calculations
        $monthlyRevenue = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->whereMonth('subscriptions.created_at', now()->month)
            ->sum('subscription_plans.price');
        
        $revenueGrowth = 15; // Percentage growth (can be calculated)
        $subscriptionGrowth = 8; // Percentage growth (can be calculated)
        
        // Subscription statistics
        $subscriptionStats = [
            'total_plans' => SubscriptionPlan::where('is_active', true)->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'trial_subscriptions' => Subscription::where('status', 'trial')->count(),
            'expired_subscriptions' => Subscription::where('status', 'expired')->count(),
        ];
        
        // Active sessions
        $activeSessions = 25;
        
        // Recent activities (placeholder data)
        $recentActivities = collect([
            (object) [
                'type' => 'New Salon',
                'user' => (object) ['name' => 'John Doe', 'avatar_url' => asset('images/default-avatar.png')],
                'description' => 'New salon "Beauty Plus" registered',
                'created_at' => now()->subHours(2)
            ],
            (object) [
                'type' => 'Subscription',
                'user' => (object) ['name' => 'Jane Smith', 'avatar_url' => asset('images/default-avatar.png')],
                'description' => 'Upgraded to Professional plan',
                'created_at' => now()->subHours(4)
            ],
            (object) [
                'type' => 'Payment',
                'user' => (object) ['name' => 'Mike Johnson', 'avatar_url' => asset('images/default-avatar.png')],
                'description' => 'Monthly subscription payment received',
                'created_at' => now()->subHours(6)
            ]
        ]);
        
        // Latest salons with owner information
        $latestSalons = Salon::with(['owner', 'subscriptionPlan'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalSalons',
            'totalUsers', 
            'activeSubscriptions',
            'newSalonsThisMonth',
            'newUsersThisMonth',
            'monthlyRevenue',
            'revenueGrowth',
            'subscriptionGrowth',
            'recentActivities',
            'latestSalons',
            'subscriptionStats',
            'activeSessions'
        ));
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats($startDate)
    {
        $currentPeriod = [
            'salons' => Salon::where('created_at', '>=', $startDate)->count(),
            'users' => User::where('created_at', '>=', $startDate)->count(),
            'revenue' => $this->calculateRevenue($startDate),
            'subscriptions' => Subscription::where('created_at', '>=', $startDate)->count(),
            'orders' => Order::where('created_at', '>=', $startDate)->count(),
            'appointments' => Appointment::where('created_at', '>=', $startDate)->count(),
        ];

        $previousPeriod = [
            'salons' => Salon::whereBetween('created_at', [
                $startDate->copy()->subDays(30), 
                $startDate
            ])->count(),
            'users' => User::whereBetween('created_at', [
                $startDate->copy()->subDays(30), 
                $startDate
            ])->count(),
            'revenue' => $this->calculateRevenue($startDate->copy()->subDays(30)),
            'subscriptions' => Subscription::whereBetween('created_at', [
                $startDate->copy()->subDays(30), 
                $startDate
            ])->count(),
            'orders' => Order::whereBetween('created_at', [
                $startDate->copy()->subDays(30), 
                $startDate
            ])->count(),
            'appointments' => Appointment::whereBetween('created_at', [
                $startDate->copy()->subDays(30), 
                $startDate
            ])->count(),
        ];

        return [
            'current' => $currentPeriod,
            'previous' => $previousPeriod,
            'growth' => $this->calculateGrowth($currentPeriod, $previousPeriod),
        ];
    }

    /**
     * Get revenue statistics
     */
    private function getRevenueStats($startDate)
    {
        $monthlyRevenue = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(subscriptions.created_at, "%Y-%m") as month, SUM(subscription_plans.price) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $planRevenue = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.created_at', '>=', $startDate)
            ->selectRaw('subscription_plans.name, COUNT(*) as count, SUM(subscription_plans.price) as revenue')
            ->groupBy('subscription_plans.id', 'subscription_plans.name')
            ->orderByDesc('revenue')
            ->get();

        return [
            'monthly' => $monthlyRevenue,
            'by_plan' => $planRevenue,
            'total' => $this->calculateRevenue($startDate),
        ];
    }

    /**
     * Get subscription statistics
     */
    private function getSubscriptionStats($startDate)
    {
        $statusCounts = Subscription::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $planDistribution = DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->selectRaw('subscription_plans.name, COUNT(*) as count')
            ->groupBy('subscription_plans.id', 'subscription_plans.name')
            ->orderByDesc('count')
            ->get();

        $monthlySubscriptions = Subscription::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'status_counts' => $statusCounts,
            'plan_distribution' => $planDistribution,
            'monthly_trend' => $monthlySubscriptions,
        ];
    }

    /**
     * Get salon statistics
     */
    private function getSalonStats($startDate)
    {
        $statusCounts = Salon::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $monthlyRegistrations = Salon::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topSalons = Salon::withCount('subscriptions')
            ->orderByDesc('subscriptions_count')
            ->take(10)
            ->get();

        return [
            'status_counts' => $statusCounts,
            'monthly_registrations' => $monthlyRegistrations,
            'top_salons' => $topSalons,
        ];
    }

    /**
     * Get user statistics
     */
    private function getUserStats($startDate)
    {
        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name, COUNT(*) as count')
            ->groupBy('roles.id', 'roles.name')
            ->get()
            ->pluck('count', 'name')
            ->toArray();

        $monthlyRegistrations = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();

        return [
            'role_counts' => $roleCounts,
            'monthly_registrations' => $monthlyRegistrations,
            'active_users' => $activeUsers,
        ];
    }

    /**
     * Get service statistics
     */
    private function getServiceStats($startDate)
    {
        $totalServices = Service::count();
        $activeServices = Service::where('is_active', true)->count();
        $servicesByCategory = Service::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        return [
            'total' => $totalServices,
            'active' => $activeServices,
            'by_category' => $servicesByCategory,
        ];
    }

    /**
     * Get product statistics
     */
    private function getProductStats($startDate)
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $productsByCategory = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return [
            'total' => $totalProducts,
            'active' => $activeProducts,
            'by_category' => $productsByCategory,
        ];
    }

    /**
     * Get appointment statistics
     */
    private function getAppointmentStats($startDate)
    {
        $statusCounts = Appointment::selectRaw('status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $monthlyAppointments = Appointment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'status_counts' => $statusCounts,
            'monthly_trend' => $monthlyAppointments,
        ];
    }

    /**
     * Get order statistics
     */
    private function getOrderStats($startDate)
    {
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $monthlyOrders = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(total_amount) as revenue')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'status_counts' => $statusCounts,
            'monthly_trend' => $monthlyOrders,
        ];
    }

    /**
     * Get content statistics
     */
    private function getContentStats($startDate)
    {
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $totalPages = Page::count();
        $publishedPages = Page::where('status', 'published')->count();

        $monthlyBlogs = Blog::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'blogs' => [
                'total' => $totalBlogs,
                'published' => $publishedBlogs,
                'monthly_trend' => $monthlyBlogs,
            ],
            'pages' => [
                'total' => $totalPages,
                'published' => $publishedPages,
            ],
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $recentUsers = User::latest()->take(5)->get();
        $recentSalons = Salon::latest()->take(5)->get();
        $recentOrders = Order::with('customer')->latest()->take(5)->get();
        $recentAppointments = Appointment::with('customer', 'service')->latest()->take(5)->get();

        return [
            'users' => $recentUsers,
            'salons' => $recentSalons,
            'orders' => $recentOrders,
            'appointments' => $recentAppointments,
        ];
    }

    /**
     * Get system health information
     */
    private function getSystemHealth()
    {
        $diskUsage = disk_free_space('/') / disk_total_space('/') * 100;
        $memoryUsage = memory_get_usage(true) / 1024 / 1024; // MB
        
        return [
            'disk_usage' => round(100 - $diskUsage, 2),
            'memory_usage' => round($memoryUsage, 2),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_size' => $this->getDatabaseSize(),
        ];
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        try {
            $result = DB::select('SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size FROM information_schema.tables WHERE table_schema = ?', [config('database.connections.mysql.database')]);
            return $result[0]->size ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calculate revenue for a given period
     */
    private function calculateRevenue($startDate)
    {
        return DB::table('subscriptions')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.created_at', '>=', $startDate)
            ->sum('subscription_plans.price');
    }

    /**
     * Calculate growth percentage
     */
    private function calculateGrowth($current, $previous)
    {
        $growth = [];
        foreach ($current as $key => $value) {
            if ($previous[$key] > 0) {
                $growth[$key] = round((($value - $previous[$key]) / $previous[$key]) * 100, 2);
            } else {
                $growth[$key] = $value > 0 ? 100 : 0;
            }
        }
        return $growth;
    }

    /**
     * Get start date based on period
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->subWeek();
            case 'month':
                return now()->subMonth();
            case 'quarter':
                return now()->subQuarter();
            case 'year':
                return now()->subYear();
            default:
                return now()->subMonth();
        }
    }
}
