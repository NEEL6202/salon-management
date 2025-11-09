@extends('layouts.modern')

@section('title', 'Salon Analytics')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Analytics & Reports</h1>
        <p class="page-subtitle">Track your salon's performance and insights</p>
    </div>
    <div class="page-actions">
        <form method="GET" class="d-flex gap-2">
            <select name="period" class="form-select" onchange="this.form.submit()">
                <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
            </select>
            <a href="{{ route('salon.analytics.export', ['period' => $period]) }}" class="btn btn-outline-primary">
                <i class="fas fa-download"></i> Export Report
            </a>
        </form>
    </div>
</div>

<!-- Revenue Overview -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">${{ number_format($revenueData['total'], 2) }}</h3>
                    <p class="stat-label">Total Revenue</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">${{ number_format($revenueData['orders_revenue'], 2) }}</h3>
                    <p class="stat-label">Product Sales</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-info">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">${{ number_format($revenueData['appointments_revenue'], 2) }}</h3>
                    <p class="stat-label">Service Revenue</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $revenueData['total_orders'] }}</h3>
                    <p class="stat-label">Total Orders</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Appointment Overview</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value">{{ $appointmentData['total'] }}</div>
                            <div class="metric-label">Total Appointments</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-success">{{ $appointmentData['completed'] }}</div>
                            <div class="metric-label">Completed</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-warning">{{ $appointmentData['pending'] }}</div>
                            <div class="metric-label">Pending</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-danger">{{ $appointmentData['cancelled'] }}</div>
                            <div class="metric-label">Cancelled</div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success">{{ $appointmentData['completion_rate'] }}%</span>
                            <span class="text-muted">Completion Rate</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger">{{ $appointmentData['cancellation_rate'] }}%</span>
                            <span class="text-muted">Cancellation Rate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Customer Insights</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-primary">{{ $customerAnalytics['new_customers'] }}</div>
                            <div class="metric-label">New Customers</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric">
                            <div class="metric-value text-success">{{ $customerAnalytics['returning_customers'] }}</div>
                            <div class="metric-label">Returning Customers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employee Performance -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Employee Performance</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Total Appointments</th>
                        <th>Completed</th>
                        <th>Revenue Generated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employeePerformance as $employee)
                    <tr>
                        <td><strong>{{ $employee['name'] }}</strong></td>
                        <td>{{ $employee['total_appointments'] }}</td>
                        <td>
                            <span class="badge bg-success">{{ $employee['completed_appointments'] }}</span>
                        </td>
                        <td>${{ number_format($employee['revenue'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No employee data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Service Popularity & Product Sales -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Services</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Bookings</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servicePopularity as $service)
                            <tr>
                                <td>{{ $service->name }}</td>
                                <td><span class="badge bg-primary">{{ $service->bookings }}</span></td>
                                <td>${{ number_format($service->price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No service data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productSales as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td><span class="badge bg-success">{{ $product->total_sold }}</span></td>
                                <td>${{ number_format($product->revenue, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No product data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Customers -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Top Customers</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Visit Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerAnalytics['top_customers'] as $customer)
                    <tr>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->email }}</td>
                        <td><span class="badge bg-primary">{{ $customer->visit_count }} visits</span></td>
                        <td>
                            <a href="{{ route('salon.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No customer data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.stat-card {
    border: none;
    box-shadow: var(--shadow-sm);
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
    margin: 0;
}

.metric {
    text-align: center;
    padding: 1rem;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

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
