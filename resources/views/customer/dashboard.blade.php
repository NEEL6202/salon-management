@extends('layouts.app')

@section('title', 'Customer Dashboard - Salon Management System')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('customer.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.appointments.index') }}">
                            <i class="fas fa-calendar-alt me-2"></i>
                            My Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.orders.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i>
                            My Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>
                            Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Welcome, {{ Auth::user()->name }}!</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('customer.appointments.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Book Appointment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Upcoming Appointments</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcomingAppointments ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                        Total Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
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
                                        Total Spent</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSpent ?? 0, 2) }}</div>
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
                                        Favorite Services</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $favoriteServices ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-heart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-calendar-plus fa-2x mb-2"></i><br>
                                        Book Appointment
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('customer.orders.create') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                                        Place Order
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('profile') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-user-edit fa-2x mb-2"></i><br>
                                        Update Profile
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="#" class="btn btn-warning btn-block">
                                        <i class="fas fa-star fa-2x mb-2"></i><br>
                                        Rate Services
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Upcoming Appointments</h6>
                        </div>
                        <div class="card-body">
                            @forelse($upcomingAppointmentsList ?? [] as $appointment)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                <div>
                                    <h6 class="mb-1">{{ $appointment->service->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $appointment->appointment_date }} at {{ $appointment->appointment_time }}
                                    </small><br>
                                    <small class="text-muted">
                                        <i class="fas fa-store me-1"></i>
                                        {{ $appointment->service->salon->name }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $appointment->status_color }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('customer.appointments.show', $appointment) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No upcoming appointments</p>
                                <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary">
                                    Book Your First Appointment
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                        </div>
                        <div class="card-body">
                            @forelse($recentOrders ?? [] as $order)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                <div>
                                    <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $order->created_at->format('M d, Y') }}
                                    </small><br>
                                    <small class="text-muted">
                                        <i class="fas fa-shopping-bag me-1"></i>
                                        {{ $order->items->count() }} items
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="h6 mb-1">${{ number_format($order->total_amount, 2) }}</div>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('customer.orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No orders yet</p>
                                <a href="{{ route('customer.orders.create') }}" class="btn btn-success">
                                    Place Your First Order
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommended Services -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recommended Services</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @forelse($recommendedServices ?? [] as $service)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        @if($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" 
                                             class="card-img-top" alt="{{ $service->name }}">
                                        @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-cut fa-3x text-muted"></i>
                                        </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $service->name }}</h6>
                                            <p class="card-text text-muted">{{ Str::limit($service->description, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h6 text-primary mb-0">${{ number_format($service->price, 2) }}</span>
                                                <a href="{{ route('customer.appointments.create', ['service_id' => $service->id]) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    Book Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-4">
                                    <i class="fas fa-cut fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No services available at the moment</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 