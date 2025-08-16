@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Appointment Details</h5>
                        <div>
                            <a href="{{ route('salon.appointments.edit', $appointment) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit Appointment
                            </a>
                            <a href="{{ route('salon.appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Appointments
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Customer</label>
                                    <div class="form-control-plaintext">
                                        {{ $appointment->customer->name ?? 'N/A' }}
                                        <br><small class="text-muted">{{ $appointment->customer->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Service</label>
                                    <div class="form-control-plaintext">
                                        {{ $appointment->service->name ?? 'N/A' }}
                                        <br><small class="text-muted">{{ $appointment->service->formatted_duration ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Date</label>
                                    <div class="form-control-plaintext">{{ $appointment->formatted_date ?? 'N/A' }}</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Time</label>
                                    <div class="form-control-plaintext">{{ $appointment->formatted_time ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Assigned Employee</label>
                                    <div class="form-control-plaintext">
                                        {{ $appointment->employee->name ?? 'Not assigned' }}
                                        @if($appointment->employee)
                                            <br><small class="text-muted">{{ $appointment->employee->roles->first()->name ?? 'Employee' }}</small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge {{ $appointment->status_badge }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Total Amount</label>
                                    <div class="form-control-plaintext">
                                        <span class="fw-bold text-success">{{ $appointment->formatted_total_amount }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Final Amount</label>
                                    <div class="form-control-plaintext">
                                        <span class="fw-bold text-success">{{ $appointment->formatted_final_amount }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Payment Status</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge {{ $appointment->payment_status_badge }}">{{ ucfirst($appointment->payment_status) }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Payment Method</label>
                                    <div class="form-control-plaintext">{{ ucfirst($appointment->payment_method ?? 'Not specified') }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes</label>
                                <div class="form-control-plaintext">
                                    {{ $appointment->notes ?: 'No notes provided.' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Appointment Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Appointment ID:</small>
                                        <div class="fw-bold">{{ $appointment->id }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Created:</small>
                                        <div class="fw-bold">{{ $appointment->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Last Updated:</small>
                                        <div class="fw-bold">{{ $appointment->updated_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>

                            @if($appointment->service)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Service Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Service Name:</small>
                                        <div class="fw-bold">{{ $appointment->service->name }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Category:</small>
                                        <div class="fw-bold">{{ ucfirst($appointment->service->category) }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Duration:</small>
                                        <div class="fw-bold">{{ $appointment->service->formatted_duration }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Price:</small>
                                        <div class="fw-bold text-success">{{ $appointment->service->formatted_price }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 