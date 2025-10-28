@extends('layouts.modern')

@section('title', 'User Details - SalonPro')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">User Details</h1>
            <p class="page-subtitle">View comprehensive user information</p>
        </div>
        <div class="page-actions d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                <span>Edit User</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>
    </div>
</div>

<!-- User Profile Overview -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-circle"></i>
            User Profile
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Profile Image -->
            <div class="col-md-3 text-center">
                <img src="{{ $user->avatar_url }}" alt="User Avatar" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover; border: 5px solid var(--theme-color);">
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">
                    <i class="fas fa-envelope me-1"></i>
                    {{ $user->email }}
                </p>
                <div class="mb-2">
                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }} px-3 py-2">
                        <i class="fas fa-{{ $user->status === 'active' ? 'check-circle' : 'times-circle' }}"></i>
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>
            
            <!-- Profile Details -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Role & Salon -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-tag text-primary"></i>
                                Role(s)
                            </label>
                            <div>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-{{ $role->name === 'super_admin' ? 'danger' : ($role->name === 'salon_owner' ? 'primary' : ($role->name === 'manager' ? 'warning' : ($role->name === 'employee' ? 'info' : 'secondary'))) }} me-1">
                                        <i class="fas fa-{{ $role->name === 'super_admin' ? 'crown' : ($role->name === 'salon_owner' ? 'store' : ($role->name === 'manager' ? 'user-tie' : ($role->name === 'employee' ? 'user' : 'user-circle'))) }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        @if($user->salon)
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-store text-primary"></i>
                                Salon
                            </label>
                            <p class="text-muted mb-0">{{ $user->salon->name }}</p>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt text-success"></i>
                                Member Since
                            </label>
                            <p class="text-muted mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone text-primary"></i>
                                Phone
                            </label>
                            <p class="text-muted mb-0">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-venus-mars text-info"></i>
                                Gender
                            </label>
                            <p class="text-muted mb-0">{{ ucfirst($user->gender ?? 'Not specified') }}</p>
                        </div>
                        
                        @if($user->date_of_birth)
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-birthday-cake text-warning"></i>
                                Date of Birth
                            </label>
                            <p class="text-muted mb-0">{{ $user->date_of_birth->format('M d, Y') }}</p>
                        </div>
                        @endif
                        
                        @if($user->address)
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                Address
                            </label>
                            <p class="text-muted mb-0">{{ $user->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Activity Stats -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-line"></i>
            User Activity Statistics
        </h3>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <div class="p-4 rounded" style="background: linear-gradient(135deg, var(--theme-color) 0%, var(--theme-color-light) 100%); color: white;">
                    <i class="fas fa-calendar-check fa-3x mb-2"></i>
                    <h3 class="mb-1">{{ $user->appointments()->count() }}</h3>
                    <p class="mb-0">Appointments</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <div class="p-4 rounded" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white;">
                    <i class="fas fa-shopping-bag fa-3x mb-2"></i>
                    <h3 class="mb-1">{{ $user->orders()->count() }}</h3>
                    <p class="mb-0">Orders</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 rounded" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white;">
                    <i class="fas fa-comments fa-3x mb-2"></i>
                    <h3 class="mb-1">{{ $user->sentMessages()->count() }}</h3>
                    <p class="mb-0">Messages</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 rounded" style="background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%); color: white;">
                    <i class="fas fa-user-plus fa-3x mb-2"></i>
                    <h3 class="mb-1">{{ $user->createdUsers()->count() }}</h3>
                    <p class="mb-0">Users Created</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Appointments -->
@if($user->appointments()->count() > 0)
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-alt"></i>
            Recent Appointments
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-cut me-1"></i>Service</th>
                        <th><i class="fas fa-store me-1"></i>Salon</th>
                        <th><i class="fas fa-clock me-1"></i>Date</th>
                        <th><i class="fas fa-info-circle me-1"></i>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->appointments()->with(['service', 'salon'])->latest()->take(5)->get() as $appointment)
                    <tr>
                        <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->salon->name ?? 'N/A' }}</td>
                        <td>
                            <small class="text-muted">
                                <i class="far fa-calendar"></i>
                                {{ $appointment->appointment_date->format('M d, Y H:i') }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }}">
                                <i class="fas fa-{{ $appointment->status === 'confirmed' ? 'check' : ($appointment->status === 'pending' ? 'clock' : 'times') }}"></i>
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
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-shopping-cart"></i>
            Recent Orders
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>Order ID</th>
                        <th><i class="fas fa-dollar-sign me-1"></i>Total</th>
                        <th><i class="fas fa-info-circle me-1"></i>Status</th>
                        <th><i class="fas fa-clock me-1"></i>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->orders()->latest()->take(5)->get() as $order)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark">#{{ $order->id }}</span>
                        </td>
                        <td>
                            <strong class="text-success">${{ number_format($order->total_amount, 2) }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                <i class="fas fa-{{ $order->status === 'completed' ? 'check' : ($order->status === 'pending' ? 'clock' : 'times') }}"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="far fa-calendar"></i>
                                {{ $order->created_at->format('M d, Y') }}
                            </small>
                        </td>
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
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-users"></i>
            Users Created by This User
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-user me-1"></i>Name</th>
                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                        <th><i class="fas fa-user-tag me-1"></i>Role</th>
                        <th><i class="fas fa-clock me-1"></i>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->createdUsers()->with('roles')->latest()->take(5)->get() as $createdUser)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $createdUser->avatar_url }}" alt="{{ $createdUser->name }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                <span>{{ $createdUser->name }}</span>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $createdUser->email }}</small>
                        </td>
                        <td>
                            @foreach($createdUser->roles as $role)
                                <span class="badge bg-secondary me-1">
                                    <i class="fas fa-{{ $role->name === 'super_admin' ? 'crown' : 'user' }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="far fa-calendar"></i>
                                {{ $createdUser->created_at->format('M d, Y') }}
                            </small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection 