@extends('layouts.modern')

@section('title', 'Admin Dashboard - SalonPro')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Admin Dashboard</h1>
            <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.salons.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i>
                <span>Add Salon</span>
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                <span>Add User</span>
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-info">
                <h3>Total Salons</h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalSalons }}</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>{{ $newSalonsThisMonth }} new this month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-info">
                <h3>Total Users</h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>{{ $newUsersThisMonth }} new this month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-info">
                <h3>Active Subscriptions</h3>
            </div>
            <div class="stat-icon">
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
            <div class="stat-info">
                <h3>Monthly Revenue</h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="stat-value">${{ number_format($monthlyRevenue, 0) }}</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>{{ $revenueGrowth }}% vs last month</span>
        </div>
    </div>
</div>

<!-- Latest Salons Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-store"></i>
            Latest Salons
        </h3>
        <div class="card-actions">
            <a href="{{ route('admin.salons.index') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Salon Name</th>
                        <th>Owner</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestSalons->take(5) as $salon)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-store text-muted"></i>
                                <strong>{{ $salon->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $salon->owner->name ?? 'N/A' }}</td>
                        <td>{{ $salon->subscriptionPlan->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $salon->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($salon->status) }}
                            </span>
                        </td>
                        <td>{{ $salon->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No salons found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 