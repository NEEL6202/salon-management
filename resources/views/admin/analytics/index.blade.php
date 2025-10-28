@extends('layouts.modern')

@section('title', 'Analytics & Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line me-2"></i>Analytics & Reports
        </h1>
        <div class="d-flex gap-2">
            <select id="periodSelect" class="form-select" onchange="changePeriod()">
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Last Week</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Last Month</option>
                <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>Last Quarter</option>
                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Last Year</option>
            </select>
            <button class="btn btn-outline-primary" onclick="exportData()">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                New Salons ({{ ucfirst($period) }})
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['overview']['current']['salons'] }}
                                @if(isset($analytics['overview']['growth']['salons']))
                                    <small class="text-{{ $analytics['overview']['growth']['salons'] >= 0 ? 'success' : 'danger' }}">
                                        <i class="fas fa-arrow-{{ $analytics['overview']['growth']['salons'] >= 0 ? 'up' : 'down' }}"></i>
                                        {{ abs($analytics['overview']['growth']['salons']) }}%
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                New Users ({{ ucfirst($period) }})
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['overview']['current']['users'] }}
                                @if(isset($analytics['overview']['growth']['users']))
                                    <small class="text-{{ $analytics['overview']['growth']['users'] >= 0 ? 'success' : 'danger' }}">
                                        <i class="fas fa-arrow-{{ $analytics['overview']['growth']['users'] >= 0 ? 'up' : 'down' }}"></i>
                                        {{ abs($analytics['overview']['growth']['users']) }}%
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Revenue ({{ ucfirst($period) }})
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($analytics['overview']['current']['revenue'], 2) }}
                                @if(isset($analytics['overview']['growth']['revenue']))
                                    <small class="text-{{ $analytics['overview']['growth']['revenue'] >= 0 ? 'success' : 'danger' }}">
                                        <i class="fas fa-arrow-{{ $analytics['overview']['growth']['revenue'] >= 0 ? 'up' : 'down' }}"></i>
                                        {{ abs($analytics['overview']['growth']['revenue']) }}%
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                New Subscriptions ({{ ucfirst($period) }})
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $analytics['overview']['current']['subscriptions'] }}
                                @if(isset($analytics['overview']['growth']['subscriptions']))
                                    <small class="text-{{ $analytics['overview']['growth']['subscriptions'] >= 0 ? 'success' : 'danger' }}">
                                        <i class="fas fa-arrow-{{ $analytics['overview']['growth']['subscriptions'] >= 0 ? 'up' : 'down' }}"></i>
                                        {{ abs($analytics['overview']['growth']['subscriptions']) }}%
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Subscription Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Subscription Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="subscriptionChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Stats Row -->
    <div class="row mb-4">
        <!-- Salon Statistics -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Salon Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6>Status Distribution</h6>
                            <canvas id="salonStatusChart" height="150"></canvas>
                        </div>
                        <div class="col-6">
                            <h6>Top Salons</h6>
                            <div class="list-group list-group-flush">
                                @foreach($analytics['salons']['top_salons']->take(5) as $salon)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $salon->name }}</span>
                                        <span class="badge bg-primary rounded-pill">{{ $salon->subscriptions_count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6>Role Distribution</h6>
                            <canvas id="userRoleChart" height="150"></canvas>
                        </div>
                        <div class="col-6">
                            <h6>Quick Stats</h6>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Active Users (30 days)</span>
                                    <span class="badge bg-success rounded-pill">{{ $analytics['users']['active_users'] }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Total Services</span>
                                    <span class="badge bg-info rounded-pill">{{ $analytics['services']['total'] }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Total Products</span>
                                    <span class="badge bg-warning rounded-pill">{{ $analytics['products']['total'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Metric</th>
                                    <th>Current Period</th>
                                    <th>Previous Period</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Salon Registrations</td>
                                    <td>{{ $analytics['overview']['current']['salons'] }}</td>
                                    <td>{{ $analytics['overview']['previous']['salons'] }}</td>
                                    <td class="text-{{ $analytics['overview']['growth']['salons'] >= 0 ? 'success' : 'danger' }}">
                                        {{ $analytics['overview']['growth']['salons'] >= 0 ? '+' : '' }}{{ $analytics['overview']['growth']['salons'] }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>User Registrations</td>
                                    <td>{{ $analytics['overview']['current']['users'] }}</td>
                                    <td>{{ $analytics['overview']['previous']['users'] }}</td>
                                    <td class="text-{{ $analytics['overview']['growth']['users'] >= 0 ? 'success' : 'danger' }}">
                                        {{ $analytics['overview']['growth']['users'] >= 0 ? '+' : '' }}{{ $analytics['overview']['growth']['users'] }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>Revenue</td>
                                    <td>${{ number_format($analytics['overview']['current']['revenue'], 2) }}</td>
                                    <td>${{ number_format($analytics['overview']['previous']['revenue'], 2) }}</td>
                                    <td class="text-{{ $analytics['overview']['growth']['revenue'] >= 0 ? 'success' : 'danger' }}">
                                        {{ $analytics['overview']['growth']['revenue'] >= 0 ? '+' : '' }}{{ $analytics['overview']['growth']['revenue'] }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>Subscriptions</td>
                                    <td>{{ $analytics['overview']['current']['subscriptions'] }}</td>
                                    <td>{{ $analytics['overview']['previous']['subscriptions'] }}</td>
                                    <td class="text-{{ $analytics['overview']['growth']['subscriptions'] >= 0 ? 'success' : 'danger' }}">
                                        {{ $analytics['overview']['growth']['subscriptions'] >= 0 ? '+' : '' }}{{ $analytics['overview']['growth']['subscriptions'] }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($analytics['revenue']['monthly']->pluck('month')),
        datasets: [{
            label: 'Monthly Revenue',
            data: @json($analytics['revenue']['monthly']->pluck('revenue')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Subscription Distribution Chart
const subscriptionCtx = document.getElementById('subscriptionChart').getContext('2d');
const subscriptionChart = new Chart(subscriptionCtx, {
    type: 'doughnut',
    data: {
        labels: @json($analytics['subscriptions']['plan_distribution']->pluck('name')),
        datasets: [{
            data: @json($analytics['subscriptions']['plan_distribution']->pluck('count')),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Salon Status Chart
const salonStatusCtx = document.getElementById('salonStatusChart').getContext('2d');
const salonStatusChart = new Chart(salonStatusCtx, {
    type: 'pie',
    data: {
        labels: @json(array_keys($analytics['salons']['status_counts'])),
        datasets: [{
            data: @json(array_values($analytics['salons']['status_counts'])),
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// User Role Chart
const userRoleCtx = document.getElementById('userRoleChart').getContext('2d');
const userRoleChart = new Chart(userRoleCtx, {
    type: 'bar',
    data: {
        labels: @json(array_keys($analytics['users']['role_counts'])),
        datasets: [{
            label: 'User Count',
            data: @json(array_values($analytics['users']['role_counts'])),
            backgroundColor: [
                '#007bff',
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function changePeriod() {
    const period = document.getElementById('periodSelect').value;
    window.location.href = `{{ route('admin.analytics.index') }}?period=${period}`;
}

function exportData() {
    const period = document.getElementById('periodSelect').value;
    const format = 'json'; // You can add format selection later
    
    fetch(`{{ route('admin.analytics.export') }}?period=${period}&format=${format}`)
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            } else {
                // Download JSON file
                const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `analytics-${period}-${new Date().toISOString().split('T')[0]}.json`;
                a.click();
                window.URL.revokeObjectURL(url);
            }
        })
        .catch(error => {
            console.error('Export error:', error);
            alert('Export failed. Please try again.');
        });
}
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-xs {
    font-size: 0.7rem !important;
}

.text-uppercase {
    text-transform: uppercase !important;
}

.h3 {
    font-size: 1.75rem !important;
}

.h5 {
    font-size: 1.25rem !important;
}

.h6 {
    font-size: 1rem !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection 