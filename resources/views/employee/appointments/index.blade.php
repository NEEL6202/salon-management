@extends('layouts.modern')

@section('title', 'My Appointments')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">My Appointments</h1>
        <p class="page-subtitle">Manage your scheduled appointments</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('employee.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Appointment
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employee.appointments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-calendar-check"></i> Appointments ({{ $appointments->total() }})
            </h5>
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
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $appointment->customer->avatar_url }}" 
                                             alt="{{ $appointment->customer->name }}" 
                                             class="rounded-circle me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ $appointment->customer->name }}</div>
                                            <small class="text-muted">{{ $appointment->customer->phone }}</small>
                                        </div>
                                    </div>
                                </td>
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
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusColor = $statusColors[$appointment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($appointment->notes)
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" 
                                              title="{{ $appointment->notes }}">
                                            {{ $appointment->notes }}
                                        </span>
                                    @else
                                        <span class="text-muted">No notes</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('employee.appointments.show', $appointment) }}" 
                                           class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($appointment->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="updateStatus({{ $appointment->id }}, 'confirmed')" 
                                                    title="Accept Appointment">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="updateStatus({{ $appointment->id }}, 'cancelled')" 
                                                    title="Reject Appointment">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @elseif($appointment->status === 'confirmed')
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="updateStatus({{ $appointment->id }}, 'completed')" 
                                                    title="Mark as Completed">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No appointments found</h5>
                    <p class="text-muted">You don't have any appointments matching your criteria.</p>
                    <a href="{{ route('employee.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Your First Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Appointment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update this appointment status to <strong id="newStatus"></strong>?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Confirm Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentAppointmentId = null;
let currentNewStatus = null;

function updateStatus(appointmentId, status) {
    currentAppointmentId = appointmentId;
    currentNewStatus = status;
    
    const statusLabels = {
        'confirmed': 'Confirmed',
        'cancelled': 'Cancelled',
        'completed': 'Completed'
    };
    
    document.getElementById('newStatus').textContent = statusLabels[status];
    
    const modal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
    modal.show();
}

document.getElementById('confirmStatusUpdate').addEventListener('click', function() {
    if (!currentAppointmentId || !currentNewStatus) {
        return;
    }
    
    fetch(`{{ url('employee/appointments') }}/${currentAppointmentId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: currentNewStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the appointment status.');
    });
});
</script>
@endpush

