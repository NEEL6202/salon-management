@extends('layouts.modern')

@section('title', 'Customer Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $customer->name }}</h1>
        <p class="page-subtitle">Customer profile and history</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.customers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Customers
        </a>
        <a href="{{ route('salon.appointments.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Book Appointment
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Customer Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $customer->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($customer->name).'&size=200' }}" 
                     alt="{{ $customer->name }}" 
                     class="rounded-circle mb-3" 
                     style="width: 120px; height: 120px; object-fit: cover;">
                <h4>{{ $customer->name }}</h4>
                <p class="text-muted">{{ $customer->email }}</p>
                <p class="text-muted">{{ $customer->phone }}</p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total Visits</span>
                        <strong>{{ $totalVisits }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Completed</span>
                        <strong class="text-success">{{ $completedVisits }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Cancelled</span>
                        <strong class="text-danger">{{ $cancelledVisits }}</strong>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total Spent</span>
                        <strong class="text-primary">${{ number_format($totalSpent, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total Orders</span>
                        <strong>{{ $totalOrders }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Orders Value</span>
                        <strong>${{ number_format($ordersValue, 2) }}</strong>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="d-flex justify-content-between">
                        <span>Last Visit</span>
                        <strong>
                            @if($lastVisit)
                                {{ $lastVisit->appointment_date->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        
        @if($favoriteServices->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Favorite Services</h5>
            </div>
            <div class="card-body">
                @foreach($favoriteServices as $service)
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $service->name }}</span>
                    <span class="badge bg-primary">{{ $service->booking_count }}x</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <!-- Appointments & Orders -->
    <div class="col-md-8">
        <!-- Appointment History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Appointment History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Service</th>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $appointment->service->name }}</td>
                                <td>{{ $appointment->employee?->name ?? 'Unassigned' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'completed' => 'success',
                                            'confirmed' => 'info',
                                            'pending' => 'warning',
                                            'cancelled' => 'danger',
                                            'in_progress' => 'primary'
                                        ];
                                        $color = $statusColors[$appointment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($appointment->status) }}</span>
                                </td>
                                <td>${{ number_format($appointment->final_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No appointments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($appointments->hasPages())
                <div class="mt-3">
                    {{ $appointments->links() }}
                </div>
                @endif
            </div>
        </div>
        
        <!-- Order History -->
        @if($orders->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->items->count() }} items</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
