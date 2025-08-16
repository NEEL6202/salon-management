@extends('layouts.app')

@section('title', 'Admin Dashboard - Salon Management System')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Admin Dashboard</h1>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.salons.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Add Salon
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Add User
            </a>
            <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-warning">
                <i class="fas fa-credit-card me-2"></i>Add Plan
            </a>
            <button class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Salons</div>
                <div class="stat-icon primary">
                    <i class="fas fa-store"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalSalons }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $newSalonsThisMonth }} this month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Users</div>
                <div class="stat-icon success">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $newUsersThisMonth }} this month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Active Subscriptions</div>
                <div class="stat-icon warning">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
            <div class="stat-value">{{ $activeSubscriptions }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $subscriptionGrowth }}% growth</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Revenue This Month</div>
                <div class="stat-icon danger">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-value">${{ number_format($monthlyRevenue, 2) }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $revenueGrowth }}% vs last month</span>
            </div>
        </div>
    </div>

    <!-- System Health & Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heartbeat me-2 text-success"></i>System Health
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="health-indicator success me-2"></div>
                                <span>Database</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="health-indicator success me-2"></div>
                                <span>File Storage</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="health-indicator success me-2"></div>
                                <span>Email Service</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="health-indicator success me-2"></div>
                                <span>Payment Gateway</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="health-indicator success me-2"></div>
                                <span>API Services</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="health-indicator success me-2"></div>
                                <span>Backup System</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-info"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-label">Avg. Response Time</div>
                                <div class="stat-value">0.8s</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Uptime</div>
                                <div class="stat-value">99.9%</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-label">Active Sessions</div>
                                <div class="stat-value">{{ $activeSessions ?? 0 }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Storage Used</div>
                                <div class="stat-value">2.4 GB</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.salons.create') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-store me-2"></i>Add New Salon
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-user-plus me-2"></i>Add New User
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-credit-card me-2"></i>Create Plan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-cog me-2"></i>Platform Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>Subscription Overview
                    </h5>
                    <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-sm btn-outline-primary">View All Plans</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="subscription-stat">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $subscriptionStats['total_plans'] ?? 0 }}</div>
                                    <div class="stat-label">Active Plans</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="subscription-stat">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $subscriptionStats['active_subscriptions'] ?? 0 }}</div>
                                    <div class="stat-label">Active Subscriptions</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="subscription-stat">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $subscriptionStats['trial_subscriptions'] ?? 0 }}</div>
                                    <div class="stat-label">Trial Subscriptions</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="subscription-stat">
                                <div class="stat-icon bg-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $subscriptionStats['expired_subscriptions'] ?? 0 }}</div>
                                    <div class="stat-label">Expired Subscriptions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Latest Salons -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Activities
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-avatar">
                                    <img src="{{ $activity->user->avatar_url }}" alt="{{ $activity->user->name }}" class="rounded-circle" width="40">
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ $activity->type }}</div>
                                    <div class="activity-description">{{ $activity->description }}</div>
                                    <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No recent activities</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-store me-2"></i>Latest Salons
                    </h5>
                    <a href="{{ route('admin.salons.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($latestSalons->count() > 0)
                        @foreach($latestSalons->take(5) as $salon)
                            <div class="salon-item">
                                <div class="salon-avatar">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="salon-content">
                                    <div class="salon-name">{{ $salon->name }}</div>
                                    <div class="salon-owner">Owner: {{ $salon->owner->name ?? 'N/A' }}</div>
                                    <div class="salon-plan">Plan: {{ $salon->subscriptionPlan->name ?? 'N/A' }}</div>
                                    <div class="salon-time">Joined: {{ $salon->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="salon-status">
                                    <span class="badge bg-{{ $salon->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($salon->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No salons found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #007bff;
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stat-title {
    font-weight: 600;
    color: #6c757d;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.stat-icon.primary { background: #007bff; }
.stat-icon.success { background: #28a745; }
.stat-icon.warning { background: #ffc107; }
.stat-icon.danger { background: #dc3545; }

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.stat-change {
    font-size: 0.9rem;
    color: #28a745;
}

.stat-change.positive { color: #28a745; }
.stat-change.negative { color: #dc3545; }

.health-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.health-indicator.success { background: #28a745; }
.health-indicator.warning { background: #ffc107; }
.health-indicator.danger { background: #dc3545; }

.stat-item {
    margin-bottom: 1rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #212529;
}

.subscription-stat {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-right: 1rem;
}

.stat-content .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-content .stat-label {
    font-size: 0.9rem;
    color: #6c757d;
}

.activity-item, .salon-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.activity-item:last-child, .salon-item:last-child {
    border-bottom: none;
}

.activity-avatar, .salon-avatar {
    margin-right: 1rem;
}

.activity-content, .salon-content {
    flex: 1;
}

.activity-title, .salon-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.activity-description, .salon-owner, .salon-plan {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.activity-time, .salon-time {
    font-size: 0.8rem;
    color: #adb5bd;
}

.salon-status {
    margin-left: auto;
}
</style>
@endsection 