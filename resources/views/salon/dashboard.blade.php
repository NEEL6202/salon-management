@extends('layouts.modern')

@section('title', 'Salon Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}!</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.profile') }}" class="btn btn-outline-primary">
            <i class="fas fa-cog"></i> Salon Profile
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $totalEmployees ?? 0 }}</h3>
                    <p class="stat-label">Total Employees</p>
                    <a href="{{ route('salon.employees.index') }}" class="stat-link">View all <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">${{ number_format($monthlyRevenue ?? 0, 2) }}</h3>
                    <p class="stat-label">Monthly Revenue</p>
                    <a href="{{ route('salon.analytics.index') }}" class="stat-link">View analytics <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $pendingAppointments ?? 0 }}</h3>
                    <p class="stat-label">Pending Appointments</p>
                    <a href="{{ route('salon.appointments.index') }}" class="stat-link">View all <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-info">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $totalServices ?? 0 }}</h3>
                    <p class="stat-label">Active Services</p>
                    <a href="{{ route('salon.services.index') }}" class="stat-link">Manage <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Quick Actions</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('salon.appointments.create') }}" class="quick-action-card">
                    <div class="quick-action-icon bg-primary">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="quick-action-content">
                        <h6>Book Appointment</h6>
                        <p class="text-muted mb-0">Schedule a new appointment</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('salon.employees.create') }}" class="quick-action-card">
                    <div class="quick-action-icon bg-success">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="quick-action-content">
                        <h6>Add Employee</h6>
                        <p class="text-muted mb-0">Hire a new team member</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('salon.services.create') }}" class="quick-action-card">
                    <div class="quick-action-icon bg-info">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="quick-action-content">
                        <h6>Add Service</h6>
                        <p class="text-muted mb-0">Create a new service</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('salon.products.create') }}" class="quick-action-card">
                    <div class="quick-action-icon bg-warning">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="quick-action-content">
                        <h6>Add Product</h6>
                        <p class="text-muted mb-0">Add inventory item</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Today's Overview -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Today's Highlights</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-primary">{{ $todayAppointments ?? 0 }}</div>
                            <div class="metric-label">Appointments Today</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-success">${{ number_format($todayRevenue ?? 0, 2) }}</div>
                            <div class="metric-label">Today's Revenue</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-info">{{ $completedAppointments ?? 0 }}</div>
                            <div class="metric-label">Completed This Month</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-warning">{{ $totalProducts ?? 0 }}</div>
                            <div class="metric-label">Products in Stock</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Employees</h5>
                <a href="{{ route('salon.employees.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentEmployees) && $recentEmployees->count() > 0)
                    <div class="employee-list">
                        @foreach($recentEmployees->take(5) as $employee)
                        <div class="employee-item">
                            <img src="{{ $employee->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($employee->name) }}" 
                                 alt="{{ $employee->name }}" class="employee-avatar">
                            <div class="employee-info">
                                <h6 class="mb-0">{{ $employee->name }}</h6>
                                <small class="text-muted">{{ $employee->roles->first()->name ?? 'Staff' }}</small>
                            </div>
                            <span class="badge bg-success">Active</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No employees yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Appointments</h5>
                <a href="{{ route('salon.appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentAppointments) && $recentAppointments->count() > 0)
                    <div class="activity-list">
                        @foreach($recentAppointments->take(5) as $appointment)
                        <div class="activity-item">
                            <div class="activity-icon bg-primary">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">{{ optional($appointment->customer)->name ?? 'Unknown Customer' }}</h6>
                                <p class="text-muted mb-0 small">
                                    {{ optional($appointment->service)->name ?? 'No Service' }} • 
                                    {{ optional($appointment->appointment_date)->format('M d, Y g:i A') ?? '--' }}
                                </p>
                            </div>
                            <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($appointment->status ?? 'unknown') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No recent appointments</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Orders</h5>
                <a href="{{ route('salon.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                    <div class="activity-list">
                        @foreach($recentOrders->take(5) as $order)
                        <div class="activity-item">
                            <div class="activity-icon bg-success">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">{{ optional($order->customer)->name ?? 'Unknown Customer' }}</h6>
                                <p class="text-muted mb-0 small">
                                    ${{ number_format($order->total_amount ?? 0, 2) }} • 
                                    {{ optional($order->created_at)->format('M d, Y') ?? '--' }}
                                </p>
                            </div>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No recent orders</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Stat Cards */
.stat-card {
    border: none;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stat-card .card-body {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.stat-link {
    font-size: 0.8125rem;
    color: var(--primary);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.2s ease;
}

.stat-link:hover {
    gap: 0.5rem;
}

/* Quick Action Cards */
.quick-action-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: white;
    border-radius: 12px;
    border: 2px solid var(--border-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.quick-action-content h6 {
    font-size: 0.9375rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.quick-action-content p {
    font-size: 0.8125rem;
}

/* Metric Boxes */
.metric {
    text-align: center;
    padding: 1rem;
}

.metric-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

/* Employee List */
.employee-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.employee-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 8px;
    background: var(--bg-secondary);
    transition: all 0.2s ease;
}

.employee-item:hover {
    background: var(--bg-tertiary);
}

.employee-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.employee-info {
    flex: 1;
}

.employee-info h6 {
    font-size: 0.9375rem;
    font-weight: 600;
    margin-bottom: 0.125rem;
}

.employee-info small {
    font-size: 0.8125rem;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: 8px;
    background: var(--bg-secondary);
    transition: all 0.2s ease;
}

.activity-item:hover {
    background: var(--bg-tertiary);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-content h6 {
    font-size: 0.9375rem;
    font-weight: 600;
    margin-bottom: 0.125rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.activity-content p {
    font-size: 0.8125rem;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    margin: 0;
}

.page-actions {
    display: flex;
    gap: 0.5rem;
}
</style>
@endsection
