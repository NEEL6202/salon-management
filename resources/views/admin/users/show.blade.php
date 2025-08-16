@extends('layouts.app')

@section('title', 'User Details - Admin Dashboard')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">User Details</h1>
            <p class="text-muted">View comprehensive user information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar_url }}" alt="User Avatar" class="rounded-circle mb-3" width="120">
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-{{ $role->name === 'super_admin' ? 'danger' : ($role->name === 'salon_owner' ? 'primary' : ($role->name === 'manager' ? 'warning' : ($role->name === 'employee' ? 'info' : 'secondary'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </span>
                        @endforeach
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    
                    @if($user->salon)
                        <div class="mb-3">
                            <strong>Salon:</strong>
                            <p class="text-muted">{{ $user->salon->name }}</p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong>Member Since:</strong>
                        <p class="text-muted">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <p class="text-muted">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Gender:</strong>
                        <p class="text-muted">{{ ucfirst($user->gender) ?? 'Not specified' }}</p>
                    </div>
                    
                    @if($user->date_of_birth)
                        <div class="mb-3">
                            <strong>Date of Birth:</strong>
                            <p class="text-muted">{{ $user->date_of_birth->format('M d, Y') }}</p>
                        </div>
                    @endif
                    
                    @if($user->address)
                        <div class="mb-3">
                            <strong>Address:</strong>
                            <p class="text-muted">{{ $user->address }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- User Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Activity</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $user->appointments()->count() }}</h4>
                                <p class="text-muted">Appointments</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $user->orders()->count() }}</h4>
                                <p class="text-muted">Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $user->sentMessages()->count() }}</h4>
                                <p class="text-muted">Messages Sent</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $user->createdUsers()->count() }}</h4>
                                <p class="text-muted">Users Created</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Appointments -->
            @if($user->appointments()->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Salon</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->appointments()->with(['service', 'salon'])->latest()->take(5)->get() as $appointment)
                                <tr>
                                    <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->salon->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->appointment_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($appointment->status) }}
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

            <!-- Recent Orders -->
            @if($user->orders()->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders()->latest()->take(5)->get() as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Created Users (for admin users) -->
            @if($user->createdUsers()->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Users Created by This User</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->createdUsers()->with('roles')->latest()->take(5)->get() as $createdUser)
                                <tr>
                                    <td>{{ $createdUser->name }}</td>
                                    <td>{{ $createdUser->email }}</td>
                                    <td>
                                        @foreach($createdUser->roles as $role)
                                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $createdUser->created_at->format('M d, Y') }}</td>
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
</div>
@endsection 