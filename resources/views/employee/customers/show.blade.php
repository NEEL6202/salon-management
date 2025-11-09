@extends('layouts.modern')

@section('title', 'Customer Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Customer Details</h1>
        <p class="page-subtitle">{{ $customer->name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('employee.customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Customers
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Customer Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($customer->avatar)
                <img src="{{ Storage::url($customer->avatar) }}" 
                     alt="{{ $customer->name }}" 
                     class="rounded-circle mb-3"
                     style="width: 120px; height: 120px; object-fit: cover;">
                @else
                <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width: 120px; height: 120px;">
                    <i class="fas fa-user fa-3x text-white"></i>
                </div>
                @endif
                
                <h4 class="mb-1">{{ $customer->name }}</h4>
                <p class="text-muted mb-3">{{ $customer->email }}</p>
                
                <div class="text-start">
                    <div class="mb-2">
                        <i class="fas fa-phone text-muted me-2"></i>
                        <span>{{ $customer->phone ?: 'No phone' }}</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-venus-mars text-muted me-2"></i>
                        <span>{{ $customer->gender ? ucfirst($customer->gender) : 'Not specified' }}</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-calendar text-muted me-2"></i>
                        <span>Member since {{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($customer->address)
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                        <span>{{ $customer->address }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Stats Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Appointments</span>
                    <span class="fw-bold">{{ $appointments->total() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Completed</span>
                    <span class="fw-bold text-success">{{ $appointments->where('status', 'completed')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Cancelled</span>
                    <span class="fw-bold text-danger">{{ $appointments->where('status', 'cancelled')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Appointment History -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history"></i> Appointment History
                </h5>
            </div>
            <div class="card-body">
                @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Date & Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $appointment->service->name }}</div>
                                    <small class="text-muted">${{ $appointment->service->price }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $appointment->service->duration }} min</span>
                                </td>
                                <td>
                                    @if($appointment->status === 'pending')
                                        <span class="badge bg-warning">{{ ucfirst($appointment->status) }}</span>
                                    @elseif($appointment->status === 'confirmed')
                                        <span class="badge bg-info">{{ ucfirst($appointment->status) }}</span>
                                    @elseif($appointment->status === 'completed')
                                        <span class="badge bg-success">{{ ucfirst($appointment->status) }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($appointment->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appointment->notes)
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                              title="{{ $appointment->notes }}">
                                            {{ $appointment->notes }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($appointments->hasPages())
                <div class="card-footer">
                    {{ $appointments->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No appointment history</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
