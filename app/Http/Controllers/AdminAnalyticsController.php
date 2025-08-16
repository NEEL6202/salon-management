<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;


class AdminAnalyticsController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at the route level
    }

    /**
     * Show analytics dashboard
     */
    public function index()
    {
        $period = request('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $analytics = [
            'overview' => $this->getOverviewStats($startDate),
            'revenue' => $this->getRevenueStats($startDate),
            'subscriptions' => $this->getSubscriptionStats($startDate),
            'salons' => $this->getSalonStats($startDate),
            'users' => $this->getUserStats($startDate),
            'services' => $this->getServiceStats($startDate),
            'products' => $this->getProductStats($startDate),
            'appointments' => $this->getAppointmentStats($startDate),
            'orders' => $this->getOrderStats($startDate),
        ];

        return view('admin.analytics.index', compact('analytics', 'period'));
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

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $data = [
            'overview' => $this->getOverviewStats($startDate),
            'revenue' => $this->getRevenueStats($startDate),
            'subscriptions' => $this->getSubscriptionStats($startDate),
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        // For CSV export, you would implement CSV generation logic here
        return response()->json(['message' => 'Export functionality to be implemented']);
    }
} 