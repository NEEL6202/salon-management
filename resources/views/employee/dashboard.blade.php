@extends('layouts.modern')

@section('title', 'Employee Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">My Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}!</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="openNewAppointmentModal()">
            <i class="fas fa-plus"></i> New Appointment
        </button>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $todayAppointments }}</h3>
                    <p class="stat-label">Today's Appointments</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $pendingAppointments }}</h3>
                    <p class="stat-label">Pending</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $completedToday }}</h3>
                    <p class="stat-label">Completed Today</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-info">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $totalCustomers }}</h3>
                    <p class="stat-label">Total Customers</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar View -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-calendar-alt"></i> My Schedule
        </h5>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Recent Appointments -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-clock"></i> Recent Appointments
        </h5>
    </div>
    <div class="card-body">
        @if($recentAppointments->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($appointment->customer->avatar)
                                                <img src="{{ Storage::url($appointment->customer->avatar) }}" 
                                                     alt="{{ $appointment->customer->name }}" 
                                                     class="rounded-circle me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $appointment->customer->name }}</div>
                                                    <small class="text-muted">{{ $appointment->customer->phone }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $appointment->service->name }}</div>
                                            <small class="text-muted">{{ $appointment->service->duration }} min</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $appointment->appointment_time }}</small>
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
                                            <div class="btn-group btn-group-sm">
                                                @if($appointment->status === 'pending')
                                                    <button class="btn btn-success" 
                                                            onclick="updateAppointmentStatus({{ $appointment->id }}, 'confirmed')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger" 
                                                            onclick="updateAppointmentStatus({{ $appointment->id }}, 'cancelled')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($appointment->status === 'confirmed')
                                                    <button class="btn btn-primary" 
                                                            onclick="updateAppointmentStatus({{ $appointment->id }}, 'completed')">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('employee.appointments.show', $appointment) }}" class="btn btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5>No Appointments Found</h5>
                            <p class="text-muted">You don't have any appointments yet</p>
                        </div>
                        @endif
                    </div>
                </div>

<!-- New Appointment Modal -->
<div class="modal fade" id="newAppointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newAppointmentForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer</label>
                                <select class="form-select" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Service</label>
                                <select class="form-select" name="service_id" required>
                                    <option value="">Select Service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }} - ${{ $service->price }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="appointment_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Time</label>
                                <input type="time" class="form-control" name="appointment_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any special requests or notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createAppointment()">Create Appointment</button>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
let calendar;

document.addEventListener('DOMContentLoaded', function() {
    initializeCalendar();
});

function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($calendarEvents),
        eventClick: function(info) {
            viewAppointmentDetails(info.event.id);
        },
        eventDidMount: function(info) {
            // Add custom styling based on status
            const status = info.event.extendedProps.status;
            if (status === 'pending') {
                info.el.style.backgroundColor = '#fff3cd';
                info.el.style.borderColor = '#ffeaa7';
                info.el.style.color = '#856404';
            } else if (status === 'confirmed') {
                info.el.style.backgroundColor = '#d1ecf1';
                info.el.style.borderColor = '#bee5eb';
                info.el.style.color = '#0c5460';
            } else if (status === 'completed') {
                info.el.style.backgroundColor = '#d4edda';
                info.el.style.borderColor = '#c3e6cb';
                info.el.style.color = '#155724';
            } else if (status === 'cancelled') {
                info.el.style.backgroundColor = '#f8d7da';
                info.el.style.borderColor = '#f5c6cb';
                info.el.style.color = '#721c24';
            }
        },
        height: 'auto',
        selectable: true,
        select: function(info) {
            // Handle date selection for new appointments
            const modal = new bootstrap.Modal(document.getElementById('newAppointmentModal'));
            document.querySelector('input[name="appointment_date"]').value = info.startStr;
            modal.show();
        }
    });
    
    calendar.render();
}

function openNewAppointmentModal() {
    const modal = new bootstrap.Modal(document.getElementById('newAppointmentModal'));
    modal.show();
}

function createAppointment() {
    const form = document.getElementById('newAppointmentForm');
    const formData = new FormData(form);
    
    fetch('{{ route("employee.appointments.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Appointment created successfully!');
            bootstrap.Modal.getInstance(document.getElementById('newAppointmentModal')).hide();
            form.reset();
            refreshCalendar();
            location.reload(); // Refresh to show new appointment
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the appointment.');
    });
}

function updateAppointmentStatus(appointmentId, status) {
    if (!confirm(`Are you sure you want to ${status} this appointment?`)) {
        return;
    }
    
    fetch(`{{ url('employee/appointments') }}/${appointmentId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Appointment ${status} successfully!`);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the appointment.');
    });
}

function viewAppointmentDetails(appointmentId) {
    fetch(`{{ url('employee/appointments') }}/${appointmentId}`)
    .then(response => response.text())
    .then(html => {
        document.getElementById('appointmentDetailsContent').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
        modal.show();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading appointment details.');
    });
}

function refreshCalendar() {
    calendar.refetchEvents();
}
</script>
@endpush

