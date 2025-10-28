@extends('layouts.modern')

@section('title', 'Settings - SalonPro')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">System Settings</h1>
        <p class="page-subtitle">Manage your application configuration and preferences</p>
    </div>
</div>

<!-- Settings Navigation -->
<div class="card mb-3">
    <div class="card-body" style="padding: 1rem;">
        <div class="settings-nav">
            <button class="settings-nav-item active" data-tab="general">
                <i class="fas fa-cog"></i>
                <span>General</span>
            </button>
            <button class="settings-nav-item" data-tab="email">
                <i class="fas fa-envelope"></i>
                <span>Email</span>
            </button>
            <button class="settings-nav-item" data-tab="payment">
                <i class="fas fa-credit-card"></i>
                <span>Payment</span>
            </button>
            <button class="settings-nav-item" data-tab="notifications">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </button>
            <button class="settings-nav-item" data-tab="security">
                <i class="fas fa-shield-alt"></i>
                <span>Security</span>
            </button>
            <button class="settings-nav-item" data-tab="appearance">
                <i class="fas fa-palette"></i>
                <span>Appearance</span>
            </button>
            <button class="settings-nav-item" data-tab="system">
                <i class="fas fa-server"></i>
                <span>System</span>
            </button>
        </div>
    </div>
</div>

<!-- Settings Content -->
<div class="settings-content">
    <!-- General Settings -->
    <div class="settings-tab active" id="general-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cog"></i>
                    General Settings
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update-group', 'general') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Site Name <span class="required">*</span></label>
                                <input type="text" class="form-control" name="site_name" 
                                       value="{{ $settings['general']['site_name'] ?? 'Salon Management System' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Timezone <span class="required">*</span></label>
                                <select class="form-select" name="timezone">
                                    <option value="UTC" {{ ($settings['general']['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ ($settings['general']['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                    <option value="America/Chicago" {{ ($settings['general']['timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                    <option value="America/Denver" {{ ($settings['general']['timezone'] ?? '') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                    <option value="America/Los_Angeles" {{ ($settings['general']['timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Site URL <span class="required">*</span></label>
                                <input type="url" class="form-control" name="site_url" 
                                       value="{{ $settings['general']['site_url'] ?? config('app.url') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Currency <span class="required">*</span></label>
                                <select class="form-select" name="currency">
                                    <option value="USD" {{ ($settings['general']['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ ($settings['general']['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ ($settings['general']['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Site Description</label>
                        <textarea class="form-control" name="site_description" rows="3">{{ $settings['general']['site_description'] ?? 'Professional salon management platform' }}</textarea>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i>
                            <span>Reset</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Settings -->
    <div class="settings-tab" id="email-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-envelope"></i>
                    Email Configuration
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update-group', 'email') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Mail Driver</label>
                                <select class="form-select" name="mail_driver">
                                    <option value="smtp" {{ ($settings['email']['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ ($settings['email']['mail_driver'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($settings['email']['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SMTP Port</label>
                                <input type="number" class="form-control" name="mail_port" 
                                       value="{{ $settings['email']['mail_port'] ?? '587' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SMTP Host</label>
                                <input type="text" class="form-control" name="mail_host" 
                                       value="{{ $settings['email']['mail_host'] ?? '' }}"
                                       placeholder="smtp.example.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">From Address</label>
                                <input type="email" class="form-control" name="mail_from_address" 
                                       value="{{ $settings['email']['mail_from_address'] ?? '' }}"
                                       placeholder="noreply@example.com">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SMTP Username</label>
                                <input type="text" class="form-control" name="mail_username" 
                                       value="{{ $settings['email']['mail_username'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SMTP Password</label>
                                <input type="password" class="form-control" name="mail_password" 
                                       value="{{ $settings['email']['mail_password'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                        <button type="button" class="btn btn-info">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send Test Email</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Settings -->
    <div class="settings-tab" id="payment-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-credit-card"></i>
                    Payment Gateway Settings
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update-group', 'payment') }}">
                    @csrf
                    @method('PUT')
                    
                    <h6 class="mb-3"><i class="fab fa-stripe"></i> Stripe Configuration</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Stripe Public Key</label>
                                <input type="text" class="form-control" name="stripe_public_key" 
                                       value="{{ $settings['payment']['stripe_public_key'] ?? '' }}"
                                       placeholder="pk_test_...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Stripe Secret Key</label>
                                <input type="password" class="form-control" name="stripe_secret_key" 
                                       value="{{ $settings['payment']['stripe_secret_key'] ?? '' }}"
                                       placeholder="sk_test_...">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="mb-3"><i class="fab fa-paypal"></i> PayPal Configuration</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">PayPal Client ID</label>
                                <input type="text" class="form-control" name="paypal_client_id" 
                                       value="{{ $settings['payment']['paypal_client_id'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">PayPal Secret</label>
                                <input type="password" class="form-control" name="paypal_secret" 
                                       value="{{ $settings['payment']['paypal_secret'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">PayPal Mode</label>
                        <select class="form-select" name="paypal_mode">
                            <option value="sandbox" {{ ($settings['payment']['paypal_mode'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="live" {{ ($settings['payment']['paypal_mode'] ?? '') == 'live' ? 'selected' : '' }}>Live (Production)</option>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notifications Settings -->
    <div class="settings-tab" id="notifications-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell"></i>
                    Notification Preferences
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update-group', 'notifications') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="email_notifications" 
                                           name="email_notifications" value="1" 
                                           {{ ($settings['notifications']['email_notifications'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications">
                                        Enable Email Notifications
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="sms_notifications" 
                                           name="sms_notifications" value="1" 
                                           {{ ($settings['notifications']['sms_notifications'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_notifications">
                                        Enable SMS Notifications
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="push_notifications" 
                                           name="push_notifications" value="1" 
                                           {{ ($settings['notifications']['push_notifications'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="push_notifications">
                                        Enable Push Notifications
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="appointment_reminders" 
                                           name="appointment_reminders" value="1" 
                                           {{ ($settings['notifications']['appointment_reminders'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="appointment_reminders">
                                        Appointment Reminders
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="booking_confirmation" 
                                           name="booking_confirmation" value="1" 
                                           {{ ($settings['notifications']['booking_confirmation'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="booking_confirmation">
                                        Booking Confirmations
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="marketing_emails" 
                                           name="marketing_emails" value="1" 
                                           {{ ($settings['notifications']['marketing_emails'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="marketing_emails">
                                        Marketing Emails
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="settings-tab" id="security-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shield-alt"></i>
                    Security Settings
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update-group', 'security') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Minimum Password Length</label>
                                <input type="number" class="form-control" name="password_min_length" 
                                       value="{{ $settings['security']['password_min_length'] ?? 8 }}" 
                                       min="6" max="20">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Session Lifetime (minutes)</label>
                                <input type="number" class="form-control" name="session_lifetime" 
                                       value="{{ $settings['security']['session_lifetime'] ?? 120 }}" 
                                       min="30" max="1440">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Max Login Attempts</label>
                                <input type="number" class="form-control" name="max_login_attempts" 
                                       value="{{ $settings['security']['max_login_attempts'] ?? 5 }}" 
                                       min="3" max="10">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="password_require_special" 
                                       name="password_require_special" value="1" 
                                       {{ ($settings['security']['password_require_special'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="password_require_special">
                                    Require Special Characters in Password
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="two_factor_auth" 
                                       name="two_factor_auth" value="1" 
                                       {{ ($settings['security']['two_factor_auth'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="two_factor_auth">
                                    Enable Two-Factor Authentication
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="recaptcha_enabled" 
                                       name="recaptcha_enabled" value="1" 
                                       {{ ($settings['security']['recaptcha_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="recaptcha_enabled">
                                    Enable reCAPTCHA
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Appearance Settings -->
    <div class="settings-tab" id="appearance-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-palette"></i>
                    Appearance Settings
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update-group', 'appearance') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Theme</label>
                                <select class="form-select" name="theme">
                                    <option value="default" {{ ($settings['appearance']['theme'] ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="dark" {{ ($settings['appearance']['theme'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                                    <option value="light" {{ ($settings['appearance']['theme'] ?? '') == 'light' ? 'selected' : '' }}>Light</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Primary Color</label>
                                <input type="color" class="form-control" name="primary_color" 
                                       value="{{ $settings['appearance']['primary_color'] ?? '#3B82F6' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Logo</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                                @if(isset($settings['appearance']['logo']))
                                    <small class="text-muted">Current: {{ $settings['appearance']['logo'] }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Favicon</label>
                                <input type="file" class="form-control" name="favicon" accept="image/*">
                                @if(isset($settings['appearance']['favicon']))
                                    <small class="text-muted">Current: {{ $settings['appearance']['favicon'] }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Footer Text</label>
                        <input type="text" class="form-control" name="footer_text" 
                               value="{{ $settings['appearance']['footer_text'] ?? '© 2024 SalonPro. All rights reserved.' }}">
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- System Settings -->
    <div class="settings-tab" id="system-tab">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            System Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td><strong>Laravel Version:</strong></td>
                                        <td>{{ $systemInfo['laravel_version'] ?? app()->version() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>PHP Version:</strong></td>
                                        <td>{{ $systemInfo['php_version'] ?? phpversion() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Database Driver:</strong></td>
                                        <td>{{ $systemInfo['database_driver'] ?? config('database.default') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Memory Limit:</strong></td>
                                        <td>{{ $systemInfo['memory_limit'] ?? ini_get('memory_limit') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Max Execution Time:</strong></td>
                                        <td>{{ $systemInfo['max_execution_time'] ?? ini_get('max_execution_time') }}s</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Upload Max Filesize:</strong></td>
                                        <td>{{ $systemInfo['upload_max_filesize'] ?? ini_get('upload_max_filesize') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-wrench"></i>
                            System Maintenance
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-warning w-100" onclick="clearCache()">
                                    <i class="fas fa-broom"></i>
                                    <span>Clear Cache</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-info w-100" onclick="optimizeDatabase()">
                                    <i class="fas fa-database"></i>
                                    <span>Optimize Database</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-secondary w-100" onclick="runSystemCheck()">
                                    <i class="fas fa-stethoscope"></i>
                                    <span>System Check</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-danger w-100" onclick="clearLogs()">
                                    <i class="fas fa-trash"></i>
                                    <span>Clear Logs</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-server" style="font-size: 3rem; color: var(--theme-color); margin-bottom: 1rem;"></i>
                        <h5>System Status</h5>
                        <p class="text-muted mb-4">Everything is running smoothly</p>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-primary mb-2">
                                <i class="fas fa-sync"></i>
                                <span>Refresh Status</span>
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="toggleMaintenanceMode()">
                                <i class="fas fa-tools"></i>
                                <span>Maintenance Mode</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Settings Navigation */
.settings-nav {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    overflow-y: hidden;
    padding-bottom: 0.5rem;
    -webkit-overflow-scrolling: touch;
}

.settings-nav::-webkit-scrollbar {
    height: 6px;
}

.settings-nav::-webkit-scrollbar-track {
    background: var(--bg-tertiary);
    border-radius: 3px;
}

.settings-nav::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

.settings-nav-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-primary);
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
    cursor: pointer;
    transition: var(--transition);
    flex-shrink: 0;
}

.settings-nav-item:hover {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-color: var(--text-tertiary);
}

.settings-nav-item.active {
    background: var(--theme-color);
    color: white;
    border-color: var(--theme-color);
}

.settings-nav-item i {
    font-size: 1rem;
}

/* Settings Content */
.settings-content {
    margin-top: 0;
}

.settings-tab {
    display: none;
}

.settings-tab.active {
    display: block;
}

/* Form Switches */
.form-check-input {
    width: 48px;
    height: 24px;
    cursor: pointer;
}

.form-check-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    cursor: pointer;
}

/* Responsive */
@media (max-width: 768px) {
    .settings-nav-item span {
        display: none;
    }
    
    .settings-nav-item {
        padding: 0.75rem;
        justify-content: center;
        min-width: 48px;
    }
    
    .settings-nav-item i {
        margin: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const navItems = document.querySelectorAll('.settings-nav-item');
    const tabs = document.querySelectorAll('.settings-tab');
    
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Remove active class from all
            navItems.forEach(nav => nav.classList.remove('active'));
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Add active class to clicked
            this.classList.add('active');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
});

// Maintenance functions
function clearCache() {
    if (confirm('Are you sure you want to clear all caches?')) {
        alert('Cache clearing functionality will be implemented');
    }
}

function optimizeDatabase() {
    if (confirm('Are you sure you want to optimize the database?')) {
        alert('Database optimization functionality will be implemented');
    }
}

function runSystemCheck() {
    alert('System check functionality will be implemented');
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all logs?')) {
        alert('Log clearing functionality will be implemented');
    }
}

function toggleMaintenanceMode() {
    if (confirm('Are you sure you want to toggle maintenance mode?')) {
        alert('Maintenance mode toggle functionality will be implemented');
    }
}
</script>
@endsection
