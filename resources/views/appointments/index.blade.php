@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Appointments</h5>
                        <a href="{{ route('salon.appointments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Book Appointment
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Date & Time</th>
                                        <th>Employee</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $appointment->customer->name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $appointment->customer->email ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $appointment->service->name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $appointment->service->formatted_duration ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $appointment->formatted_date }}</div>
                                                    <small class="text-muted">{{ $appointment->formatted_time }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $appointment->employee->name ?? 'Not assigned' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $appointment->status_badge }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">{{ $appointment->formatted_final_amount }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $appointment->payment_status_badge }}">{{ ucfirst($appointment->payment_status) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('salon.appointments.show', $appointment) }}" 
                                                       class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('salon.appointments.edit', $appointment) }}" 
                                                       class="btn btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                                        <form action="{{ route('salon.appointments.destroy', $appointment) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to cancel this appointment?')"
                                                              style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <h4>No Appointments Found</h4>
                            <p class="text-muted">You haven't booked any appointments yet. Start by booking your first appointment.</p>
                            <a href="{{ route('salon.appointments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Book Your First Appointment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 