@extends('layouts.app')

@section('title', 'Subscription Plans Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Subscription Plans Management</h1>
            <p class="text-muted">Manage subscription plans and monitor subscription status</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Create New Plan
            </a>
            <button class="btn btn-outline-primary" onclick="exportData()">
                <i class="fas fa-download me-2"></i>Export Data
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalSubscriptions }}</div>
                    <div class="stat-label">Total Subscriptions</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $activeSubscriptions }}</div>
                    <div class="stat-label">Active Subscriptions</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-warning text-white">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $trialSubscriptions }}</div>
                    <div class="stat-label">Trial Subscriptions</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-info text-white">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">${{ number_format($monthlyRevenue, 2) }}</div>
                    <div class="stat-label">Monthly Revenue</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Plans Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Subscription Plans
            </h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Plan Name</th>
                            <th>Price</th>
                            <th>Billing Cycle</th>
                            <th>Features</th>
                            <th>Subscribers</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="plan-icon me-3">
                                            <i class="fas fa-{{ $plan->is_popular ? 'star' : 'credit-card' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $plan->name }}</div>
                                            @if($plan->is_popular)
                                                <span class="badge bg-warning text-dark">Popular</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">${{ number_format($plan->price, 2) }}</div>
                                    <small class="text-muted">{{ ucfirst($plan->billing_cycle) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($plan->billing_cycle) }}</span>
                                </td>
                                <td>
                                    <div class="plan-features">
                                        <div class="feature-item">
                                            <i class="fas fa-users me-1"></i>{{ $plan->max_employees }} employees
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-concierge-bell me-1"></i>{{ $plan->max_services }} services
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-box me-1"></i>{{ $plan->max_products }} products
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="subscription-count me-2">
                                            {{ $plan->subscriptions_count }}
                                        </div>
                                        @if($plan->subscriptions_count > 0)
                                            <div class="subscription-breakdown">
                                                <small class="text-success">{{ $plan->subscriptions()->where('status', 'active')->count() }} active</small><br>
                                                <small class="text-warning">{{ $plan->subscriptions()->where('status', 'trial')->count() }} trial</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.subscription-plans.show', $plan) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.subscription-plans.edit', $plan) }}" 
                                           class="btn btn-outline-warning" title="Edit Plan">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="Delete Plan"
                                                onclick="deletePlan({{ $plan->id }}, '{{ $plan->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No subscription plans found</p>
                                        <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-primary">
                                            Create Your First Plan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Subscription Analytics -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Subscription Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="subscriptionChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Revenue Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Subscriptions -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>Recent Subscriptions
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Salon</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th>Next Billing</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentSubscriptions = \App\Models\Subscription::with(['salon', 'subscriptionPlan'])
                                ->latest()
                                ->take(10)
                                ->get();
                        @endphp
                        
                        @forelse($recentSubscriptions as $subscription)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="salon-avatar me-2">
                                            <i class="fas fa-store"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $subscription->salon->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $subscription->salon->owner->name ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $subscription->subscriptionPlan->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($subscription->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($subscription->status === 'trial')
                                        <span class="badge bg-warning">Trial</span>
                                    @elseif($subscription->status === 'expired')
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $subscription->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($subscription->next_billing_date)
                                        {{ $subscription->next_billing_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-warning" title="Edit Subscription">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">
                                    No recent subscriptions found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Subscription Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the subscription plan "<strong id="planName"></strong>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Plan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function deletePlan(planId, planName) {
    document.getElementById('planName').textContent = planName;
    document.getElementById('deleteForm').action = `/admin/subscription-plans/${planId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function exportData() {
    // Add export functionality
    alert('Export functionality will be implemented here');
}

// Subscription Distribution Chart
document.addEventListener('DOMContentLoaded', function() {
    const subscriptionCtx = document.getElementById('subscriptionChart').getContext('2d');
    new Chart(subscriptionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Trial', 'Expired'],
            datasets: [{
                data: [{{ $activeSubscriptions }}, {{ $trialSubscriptions }}, {{ $totalSubscriptions - $activeSubscriptions - $trialSubscriptions }}],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue',
                data: [15000, 18000, 22000, 25000, 28000, {{ $monthlyRevenue }}],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
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
});
</script>

<style>
.stat-card {
    border-radius: 10px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 2rem;
    margin-right: 1rem;
}

.stat-content .stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-content .stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.plan-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.plan-features .feature-item {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.subscription-count {
    font-weight: 600;
    font-size: 1.1rem;
}

.subscription-breakdown {
    font-size: 0.8rem;
}

.salon-avatar {
    width: 32px;
    height: 32px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endsection 