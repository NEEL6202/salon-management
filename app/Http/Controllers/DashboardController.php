<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->salon_id) {
            return $this->salonDashboard();
        } else {
            return $this->customerDashboard();
        }
    }

    private function adminDashboard()
    {
        // Basic statistics
        $totalSalons = Salon::count();
        $totalUsers = User::count();
        $activeSubscriptions = Salon::where('status', 'active')->count();
        
        // Monthly statistics
        $newSalonsThisMonth = Salon::whereMonth('created_at', now()->month)->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        
        // Revenue calculations (placeholder data for now)
        $monthlyRevenue = 25000; // This would come from actual subscription payments
        $revenueGrowth = 15; // Percentage growth
        $subscriptionGrowth = 8; // Percentage growth
        
        // Subscription statistics
        $subscriptionStats = [
            'total_plans' => \App\Models\SubscriptionPlan::where('is_active', true)->count(),
            'active_subscriptions' => \App\Models\Subscription::where('status', 'active')->count(),
            'trial_subscriptions' => \App\Models\Subscription::where('status', 'trial')->count(),
            'expired_subscriptions' => \App\Models\Subscription::where('status', 'expired')->count(),
        ];
        
        // Active sessions (placeholder - would come from actual session tracking)
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

    private function salonDashboard()
    {
        $salon = Auth::user()->salon;
        
        // Statistics
        $todayAppointments = $salon->appointments()->whereDate('appointment_date', today())->count();
        $monthlyRevenue = 5000; // Placeholder - would come from actual orders
        $activeServices = $salon->services()->count();
        $pendingOrders = $salon->orders()->where('status', 'pending')->count();
        
        // Recent data
        $recentAppointments = $salon->appointments()
            ->with(['customer', 'service', 'employee'])
            ->latest()
            ->take(10)
            ->get();

        $recentOrders = $salon->orders()
            ->with(['customer', 'items.product'])
            ->latest()
            ->take(10)
            ->get();

        return view('salon.dashboard', compact(
            'todayAppointments',
            'monthlyRevenue', 
            'activeServices',
            'pendingOrders',
            'recentAppointments',
            'recentOrders'
        ));
    }

    private function customerDashboard()
    {
        $user = Auth::user();
        
        // Statistics
        $upcomingAppointments = $user->appointments()->where('appointment_date', '>', now())->count();
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total_amount');
        
        // Recent data
        $upcomingAppointmentsList = $user->appointments()
            ->with(['salon', 'service', 'employee'])
            ->where('appointment_date', '>', now())
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        $recentOrders = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'upcomingAppointments',
            'totalOrders',
            'totalSpent',
            'upcomingAppointmentsList',
            'recentOrders'
        ));
    }
} 