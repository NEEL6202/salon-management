@extends('layouts.app')

@section('title', 'Employee Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<style>
.calendar-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.appointment-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.appointment-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
}

.appointment-body {
    padding: 20px;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1ecf1; color: #0c5460; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 5px 12px;
    font-size: 12px;
}

.fc-event {
    cursor: pointer;
    border-radius: 5px;
}

.fc-event-title {
    font-weight: 600;
}

.fc-toolbar-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.fc-button {
    background: #667eea !important;
    border-color: #667eea !important;
    border-radius: 5px !important;
}

.fc-button:hover {
    background: #5a6fd8 !important;
    border-color: #5a6fd8 !important;
}

.fc-button:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
}

@media (max-width: 768px) {
    .quick-stats {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Employee Dashboard</h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" onclick="openNewAppointmentModal()">
                        <i class="fas fa-plus"></i> New Appointment
                    </button>
                    <button class="btn btn-outline-secondary" onclick="refreshCalendar()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-number">{{ $todayAppointments }}</div>
            <div class="stat-label">Today's Appointments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $pendingAppointments }}</div>
            <div class="stat-label">Pending Appointments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalCustomers }}</div>
            <div class="stat-label">Total Customers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $completedToday }}</div>
            <div class="stat-label">Completed Today</div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="calendar-container">
        <div id="calendar"></div>
    </div>

    <!-- Recent Appointments -->
    <div class="row">
        <div class="col-12">
            <div class="appointment-card">
                <div class="appointment-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Recent Appointments
                    </h5>
                </div>
                <div class="appointment-body">
                    @if($recentAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                                <img src="{{ $appointment->customer->avatar_url }}" 
                                                     alt="{{ $appointment->customer->name }}" 
                                                     class="customer-avatar me-2">
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
                                            <span class="status-badge status-{{ $appointment->status }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                @if($appointment->status === 'pending')
                                                    <button class="btn btn-success btn-sm" 
                                                            onclick="updateAppointmentStatus({{ $appointment->id }}, 'confirmed')">
                                                        <i class="fas fa-check"></i> Accept
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="updateAppointmentStatus({{ $appointment->id }}, 'cancelled')">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                @elseif($appointment->status === 'confirmed')
                                                    <button class="btn btn-primary btn-sm" 
                                                            onclick="updateAppointmentStatus({{ $appointment->id }}, 'completed')">
                                                        <i class="fas fa-check-double"></i> Complete
                                                    </button>
                                                @endif
                                                <button class="btn btn-info btn-sm" 
                                                        onclick="viewAppointmentDetails({{ $appointment->id }})">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No appointments found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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

