@extends('layouts.modern')

@section('title', 'Calendar')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Appointment Calendar</h1>
        <p class="page-subtitle">View and manage your appointments</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Appointment
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter by Employee</label>
                <select id="employeeFilter" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $employeeId == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Calendar -->
<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetails">
                <!-- Appointment details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="editAppointmentBtn" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var employeeFilter = document.getElementById('employeeFilter');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(info, successCallback, failureCallback) {
            var url = '{{ route("salon.calendar.events") }}';
            var params = new URLSearchParams({
                start: info.startStr,
                end: info.endStr,
                employee_id: employeeFilter.value || ''
            });
            
            fetch(url + '?' + params)
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        },
        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;
            
            var html = `
                <div class="mb-3">
                    <strong>Customer:</strong> ${props.customer}<br>
                    <strong>Phone:</strong> ${props.phone}<br>
                    <strong>Service:</strong> ${props.service}<br>
                    <strong>Employee:</strong> ${props.employee}<br>
                    <strong>Status:</strong> <span class="badge bg-${getStatusBadge(props.status)}">${props.status}</span><br>
                    <strong>Amount:</strong> ${props.amount}<br>
                    <strong>Time:</strong> ${event.start.toLocaleString()}
                </div>
            `;
            
            document.getElementById('appointmentDetails').innerHTML = html;
            document.getElementById('editAppointmentBtn').href = '/salon/appointments/' + event.id + '/edit';
            
            var modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
            modal.show();
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short'
        }
    });
    
    calendar.render();
    
    // Reload calendar when filter changes
    employeeFilter.addEventListener('change', function() {
        calendar.refetchEvents();
    });
    
    function getStatusBadge(status) {
        const badges = {
            'confirmed': 'success',
            'completed': 'primary',
            'cancelled': 'danger',
            'in_progress': 'warning',
            'pending': 'secondary'
        };
        return badges[status] || 'secondary';
    }
});
</script>
@endpush
