@extends('layouts.modern')

@section('title', 'Salon Settings')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Salon Settings</h1>
        <p class="page-subtitle">Configure your salon preferences</p>
    </div>
</div>

<form method="POST" action="{{ route('salon.settings.update') }}">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Appointment Settings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Appointment Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Default Appointment Duration (minutes)</label>
                        <input type="number" name="appointment_duration" class="form-control" 
                               value="{{ $settings['appointment_duration'] ?? 60 }}" min="15" max="480">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Buffer Time Between Appointments (minutes)</label>
                        <input type="number" name="booking_buffer_time" class="form-control" 
                               value="{{ $settings['booking_buffer_time'] ?? 15 }}" min="0" max="60">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Advance Booking Days</label>
                        <input type="number" name="advance_booking_days" class="form-control" 
                               value="{{ $settings['advance_booking_days'] ?? 30 }}" min="1" max="365">
                        <small class="text-muted">How many days in advance customers can book</small>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_confirm_appointments" 
                               id="autoConfirm" {{ ($settings['auto_confirm_appointments'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="autoConfirm">
                            Auto-confirm appointments
                        </label>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="allow_online_booking" 
                               id="onlineBooking" {{ ($settings['allow_online_booking'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="onlineBooking">
                            Allow online booking
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notification Settings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notification Settings</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="send_appointment_reminders" 
                               id="sendReminders" {{ ($settings['send_appointment_reminders'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="sendReminders">
                            Send appointment reminders
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reminder Time (hours before)</label>
                        <input type="number" name="reminder_hours_before" class="form-control" 
                               value="{{ $settings['reminder_hours_before'] ?? 24 }}" min="1" max="72">
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="send_email_notifications" 
                               id="emailNotif" {{ ($settings['send_email_notifications'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="emailNotif">
                            Email notifications
                        </label>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="send_sms_notifications" 
                               id="smsNotif" {{ ($settings['send_sms_notifications'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="smsNotif">
                            SMS notifications
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Settings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Settings</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="require_prepayment" 
                               id="requirePrepayment" {{ ($settings['require_prepayment'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requirePrepayment">
                            Require prepayment for bookings
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Prepayment Percentage</label>
                        <div class="input-group">
                            <input type="number" name="prepayment_percentage" class="form-control" 
                                   value="{{ $settings['prepayment_percentage'] ?? 50 }}" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cancellation Policy -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cancellation Policy</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Policy Text</label>
                        <textarea name="cancellation_policy" class="form-control" rows="6" 
                                  placeholder="Enter your cancellation policy...">{{ $settings['cancellation_policy'] ?? '' }}</textarea>
                        <small class="text-muted">This will be shown to customers when booking</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>
</form>
@endsection
