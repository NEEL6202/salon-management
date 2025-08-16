@extends('layouts.app')

@section('title', 'Salon Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Welcome back, {{ Auth::user()->name ?? 'User' }}!</h2>
                            @if(isset($salon))
                                <p class="mb-0">{{ $salon->name ?? 'Salon' }} - {{ $salon->address ?? 'Address not set' }}</p>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end">
                                <div class="me-3">
                                    <h4 class="mb-0">{{ $todayAppointments ?? 0 }}</h4>
                                    <small>Today's Appointments</small>
                                </div>
                                <div>
                                    <h4 class="mb-0">${{ number_format($todayRevenue ?? 0, 2) }}</h4>
                                    <small>Today's Revenue</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $totalEmployees = $totalEmployees ?? 0;
            $monthlyRevenue = $monthlyRevenue ?? 0;
            $pendingAppointments = $pendingAppointments ?? 0;
            $totalServices = $totalServices ?? 0;
            $totalProducts = $totalProducts ?? 0;
        @endphp

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Employees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($monthlyRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Pending Appointments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingAppointments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Services & Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalServices }}/{{ $totalProducts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-concierge-bell fa-2x text-gray-300"></i>
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
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('salon.employees.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus"></i> Add Employee
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('salon.services.create') }}" class="btn btn-success w-100">
                                <i class="fas fa-plus"></i> Add Service
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('salon.products.create') }}" class="btn btn-info w-100">
                                <i class="fas fa-box"></i> Add Product
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('salon.appointments.create') }}" class="btn btn-warning w-100">
                                <i class="fas fa-calendar-plus"></i> Book Appointment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Appointments</h5>
                    <a href="{{ route('salon.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentAppointments) && $recentAppointments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentAppointments as $appointment)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ optional($appointment->customer)->name ?? 'Unknown Customer' }}</h6>
                                            <small class="text-muted">
                                                {{ optional($appointment->service)->name ?? 'No Service' }} -
                                                {{ optional($appointment->appointment_date)->format('M d, Y g:i A') ?? '--' }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($appointment->status ?? 'unknown') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No recent appointments</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                    <a href="{{ route('salon.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentOrders as $order)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Order #{{ $order->order_number ?? 'N/A' }}</h6>
                                            <small class="text-muted">
                                                {{ optional($order->customer)->name ?? 'Unknown Customer' }} - 
                                                {{ optional($order->created_at)->format('M d, Y') ?? '--' }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">${{ number_format($order->total_amount ?? 0, 2) }}</div>
                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status ?? 'unknown') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No recent orders</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Employee Activity</h5>
                    <a href="{{ route('salon.employees.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentEmployees) && $recentEmployees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEmployees as $employee)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($employee->avatar)
                                                        <img src="{{ Storage::url($employee->avatar) }}"
                                                             alt="{{ $employee->name }}" class="rounded-circle me-2"
                                                             width="32" height="32">
                                                    @else
                                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                             style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $employee->name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $employee->email ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($employee->roles && $employee->roles->count() > 0)
                                                    @foreach($employee->roles as $role)
                                                        <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-secondary">No Role</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($employee->status ?? 'unknown') }}
                                                </span>
                                            </td>
                                            <td>{{ optional($employee->created_at)->format('M d, Y') ?? '--' }}</td>
                                            <td>
                                                @if($employee->id)
                                                    <a href="{{ route('salon.employees.show', $employee->id) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('salon.employees.edit', $employee->id) }}"
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No employees found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
