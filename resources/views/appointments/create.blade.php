@extends('layouts.modern')

@section('title', 'Book Appointment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Book New Appointment</h5>
                        <a href="{{ route('salon.appointments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Appointments
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('salon.appointments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_id" class="form-label">Customer *</label>
                                        <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} ({{ $customer->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="service_id" class="form-label">Service *</label>
                                        <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                            <option value="">Select Service</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" 
                                                        data-price="{{ $service->price }}" 
                                                        data-duration="{{ $service->duration }}"
                                                        {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->formatted_duration }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="appointment_date" class="form-label">Date *</label>
                                        <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                               id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                                        @error('appointment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="appointment_time" class="form-label">Time *</label>
                                        <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                               id="appointment_time" name="appointment_time" value="{{ old('appointment_time') }}" required>
                                        @error('appointment_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="assigned_to" class="form-label">Assign to Employee</label>
                                        <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                            <option value="">Select Employee (Optional)</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }} ({{ $employee->roles->first()->name ?? 'Employee' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Appointment Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Selected Service</label>
                                            <div id="selected-service" class="text-muted">No service selected</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Duration</label>
                                            <div id="service-duration" class="text-muted">-</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Price</label>
                                            <div id="service-price" class="fw-bold text-success">$0.00</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Date & Time</label>
                                            <div id="appointment-datetime" class="text-muted">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('salon.appointments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const appointmentDate = document.getElementById('appointment_date');
    const appointmentTime = document.getElementById('appointment_time');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    appointmentDate.min = today;
    
    function updateSummary() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const selectedService = document.getElementById('selected-service');
        const serviceDuration = document.getElementById('service-duration');
        const servicePrice = document.getElementById('service-price');
        const appointmentDatetime = document.getElementById('appointment-datetime');
        
        if (selectedOption.value) {
            selectedService.textContent = selectedOption.text;
            serviceDuration.textContent = selectedOption.dataset.duration + ' minutes';
            servicePrice.textContent = '$' + parseFloat(selectedOption.dataset.price).toFixed(2);
        } else {
            selectedService.textContent = 'No service selected';
            serviceDuration.textContent = '-';
            servicePrice.textContent = '$0.00';
        }
        
        if (appointmentDate.value && appointmentTime.value) {
            const date = new Date(appointmentDate.value + 'T' + appointmentTime.value);
            appointmentDatetime.textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } else {
            appointmentDatetime.textContent = '-';
        }
    }
    
    serviceSelect.addEventListener('change', updateSummary);
    appointmentDate.addEventListener('change', updateSummary);
    appointmentTime.addEventListener('change', updateSummary);
    
    // Initial update
    updateSummary();
});
</script>
@endpush 
