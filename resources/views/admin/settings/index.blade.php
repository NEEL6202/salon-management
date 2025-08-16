@extends('layouts.app')

@section('title', 'System Settings')

@push('styles')
<style>
.settings-tabs-container {
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1rem;
}

.settings-tabs-container::-webkit-scrollbar {
    height: 6px;
}

.settings-tabs-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.settings-tabs-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.settings-tabs-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

#settingsTabs {
    display: flex;
    flex-wrap: nowrap;
    min-width: max-content;
}

#settingsTabs .nav-item {
    flex-shrink: 0;
    margin-right: 2px;
}

#settingsTabs .nav-link {
    white-space: nowrap;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    border-radius: 0.25rem 0.25rem 0 0;
    border: 1px solid transparent;
    color: #6c757d;
    background-color: transparent;
    transition: all 0.15s ease-in-out;
}

#settingsTabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
    background-color: #f8f9fa;
    color: #495057;
}

#settingsTabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    border-bottom-color: #fff;
}

#settingsTabs .nav-link i {
    margin-right: 0.5rem;
    width: 16px;
    text-align: center;
}

@media (max-width: 768px) {
    #settingsTabs .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }
    
    #settingsTabs .nav-link i {
        margin-right: 0.25rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">System Settings</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="card">
        <div class="card-body">
            <div class="settings-tabs-container">
                <ul class="nav nav-tabs nav-tabs-solid" id="settingsTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                        <i class="fas fa-cog"></i> General
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab">
                        <i class="fas fa-credit-card"></i> Payment
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab">
                        <i class="fas fa-bell"></i> Notifications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab">
                        <i class="fas fa-shield-alt"></i> Security
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="appearance-tab" data-toggle="tab" href="#appearance" role="tab">
                        <i class="fas fa-palette"></i> Appearance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="integrations-tab" data-toggle="tab" href="#integrations" role="tab">
                        <i class="fas fa-plug"></i> Integrations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="backup-tab" data-toggle="tab" href="#backup" role="tab">
                        <i class="fas fa-database"></i> Backup
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="system-tab" data-toggle="tab" href="#system" role="tab">
                        <i class="fas fa-server"></i> System
                    </a>
                </li>
                </ul>
            </div>

            <div class="tab-content mt-4" id="settingsTabContent">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'general') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Site Name</label>
                                    <input type="text" class="form-control" name="site_name" value="{{ $settings['general']['site_name'] ?? 'Salon Management System' }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Site Description</label>
                                    <textarea class="form-control" name="site_description" rows="3">{{ $settings['general']['site_description'] ?? 'Professional salon management platform' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Site URL</label>
                                    <input type="url" class="form-control" name="site_url" value="{{ $settings['general']['site_url'] ?? config('app.url') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Timezone</label>
                                    <select class="form-control" name="timezone">
                                        <option value="UTC" {{ ($settings['general']['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ ($settings['general']['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                        <option value="America/Chicago" {{ ($settings['general']['timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                        <option value="America/Denver" {{ ($settings['general']['timezone'] ?? '') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                        <option value="America/Los_Angeles" {{ ($settings['general']['timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select class="form-control" name="currency">
                                        <option value="USD" {{ ($settings['general']['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="EUR" {{ ($settings['general']['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="GBP" {{ ($settings['general']['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save General Settings</button>
                    </form>
                </div>

                <!-- Email Settings -->
                <div class="tab-pane fade" id="email" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'email') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mail Driver</label>
                                    <select class="form-control" name="mail_driver">
                                        <option value="smtp" {{ ($settings['email']['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendmail" {{ ($settings['email']['mail_driver'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="mailgun" {{ ($settings['email']['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>SMTP Host</label>
                                    <input type="text" class="form-control" name="mail_host" value="{{ $settings['email']['mail_host'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>SMTP Port</label>
                                    <input type="number" class="form-control" name="mail_port" value="{{ $settings['email']['mail_port'] ?? '587' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SMTP Username</label>
                                    <input type="text" class="form-control" name="mail_username" value="{{ $settings['email']['mail_username'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>SMTP Password</label>
                                    <input type="password" class="form-control" name="mail_password" value="{{ $settings['email']['mail_password'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>From Address</label>
                                    <input type="email" class="form-control" name="mail_from_address" value="{{ $settings['email']['mail_from_address'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Email Settings</button>
                    </form>
                </div>

                <!-- Payment Settings -->
                <div class="tab-pane fade" id="payment" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'payment') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Stripe Configuration</h6>
                                <div class="form-group">
                                    <label>Stripe Public Key</label>
                                    <input type="text" class="form-control" name="stripe_public_key" value="{{ $settings['payment']['stripe_public_key'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Stripe Secret Key</label>
                                    <input type="password" class="form-control" name="stripe_secret_key" value="{{ $settings['payment']['stripe_secret_key'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>PayPal Configuration</h6>
                                <div class="form-group">
                                    <label>PayPal Client ID</label>
                                    <input type="text" class="form-control" name="paypal_client_id" value="{{ $settings['payment']['paypal_client_id'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>PayPal Secret</label>
                                    <input type="password" class="form-control" name="paypal_secret" value="{{ $settings['payment']['paypal_secret'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>PayPal Mode</label>
                                    <select class="form-control" name="paypal_mode">
                                        <option value="sandbox" {{ ($settings['payment']['paypal_mode'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                        <option value="live" {{ ($settings['payment']['paypal_mode'] ?? '') == 'live' ? 'selected' : '' }}>Live</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Payment Settings</button>
                    </form>
                </div>

                <!-- System Settings -->
                <div class="tab-pane fade" id="system" role="tabpanel">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">System Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Laravel Version:</strong></td>
                                                    <td>{{ $systemInfo['laravel_version'] ?? 'Unknown' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>PHP Version:</strong></td>
                                                    <td>{{ $systemInfo['php_version'] ?? 'Unknown' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Database Driver:</strong></td>
                                                    <td>{{ $systemInfo['database_driver'] ?? 'Unknown' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Memory Limit:</strong></td>
                                                    <td>{{ $systemInfo['memory_limit'] ?? 'Unknown' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Max Execution Time:</strong></td>
                                                    <td>{{ $systemInfo['max_execution_time'] ?? 'Unknown' }}s</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Upload Max Filesize:</strong></td>
                                                    <td>{{ $systemInfo['upload_max_filesize'] ?? 'Unknown' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="mb-0">System Maintenance</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-warning btn-block mb-2" onclick="clearCache()">
                                                <i class="fas fa-broom"></i> Clear Cache
                                            </button>
                                            <button type="button" class="btn btn-info btn-block mb-2" onclick="optimizeDatabase()">
                                                <i class="fas fa-database"></i> Optimize Database
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-secondary btn-block mb-2" onclick="runSystemCheck()">
                                                <i class="fas fa-stethoscope"></i> System Check
                                            </button>
                                            <button type="button" class="btn btn-danger btn-block mb-2" onclick="clearLogs()">
                                                <i class="fas fa-trash"></i> Clear Logs
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-dark btn-block" onclick="toggleMaintenanceMode()">
                                                <i class="fas fa-tools"></i> Toggle Maintenance Mode
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">System Overview</h6>
                                    <p class="card-text">View system information and perform maintenance tasks.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div class="tab-pane fade" id="notifications" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'notifications') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="email_notifications" name="email_notifications" value="1" {{ ($settings['notifications']['email_notifications'] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="email_notifications">Enable Email Notifications</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="sms_notifications" name="sms_notifications" value="1" {{ ($settings['notifications']['sms_notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="sms_notifications">Enable SMS Notifications</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="push_notifications" name="push_notifications" value="1" {{ ($settings['notifications']['push_notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="push_notifications">Enable Push Notifications</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="appointment_reminders" name="appointment_reminders" value="1" {{ ($settings['notifications']['appointment_reminders'] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="appointment_reminders">Appointment Reminders</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="booking_confirmation" name="booking_confirmation" value="1" {{ ($settings['notifications']['booking_confirmation'] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="booking_confirmation">Booking Confirmation</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="marketing_emails" name="marketing_emails" value="1" {{ ($settings['notifications']['marketing_emails'] ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="marketing_emails">Marketing Emails</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Notification Settings</button>
                    </form>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'security') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minimum Password Length</label>
                                    <input type="number" class="form-control" name="password_min_length" value="{{ $settings['security']['password_min_length'] ?? 8 }}" min="6" max="20">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="password_require_special" name="password_require_special" value="1" {{ ($settings['security']['password_require_special'] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="password_require_special">Require Special Characters</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="two_factor_auth" name="two_factor_auth" value="1" {{ ($settings['security']['two_factor_auth'] ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="two_factor_auth">Enable Two-Factor Authentication</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Session Lifetime (minutes)</label>
                                    <input type="number" class="form-control" name="session_lifetime" value="{{ $settings['security']['session_lifetime'] ?? 120 }}" min="30" max="1440">
                                </div>
                                <div class="form-group">
                                    <label>Max Login Attempts</label>
                                    <input type="number" class="form-control" name="max_login_attempts" value="{{ $settings['security']['max_login_attempts'] ?? 5 }}" min="3" max="10">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="recaptcha_enabled" name="recaptcha_enabled" value="1" {{ ($settings['security']['recaptcha_enabled'] ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="recaptcha_enabled">Enable reCAPTCHA</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Security Settings</button>
                    </form>
                </div>

                <!-- Appearance Settings -->
                <div class="tab-pane fade" id="appearance" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'appearance') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Theme</label>
                                    <select class="form-control" name="theme">
                                        <option value="default" {{ ($settings['appearance']['theme'] ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="dark" {{ ($settings['appearance']['theme'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                                        <option value="light" {{ ($settings['appearance']['theme'] ?? '') == 'light' ? 'selected' : '' }}>Light</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Primary Color</label>
                                    <input type="color" class="form-control" name="primary_color" value="{{ $settings['appearance']['primary_color'] ?? '#3B82F6' }}">
                                </div>
                                <div class="form-group">
                                    <label>Logo</label>
                                    <input type="file" class="form-control-file" name="logo" accept="image/*">
                                    @if($settings['appearance']['logo'])
                                        <small class="form-text text-muted">Current: {{ $settings['appearance']['logo'] }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Secondary Color</label>
                                    <input type="color" class="form-control" name="secondary_color" value="{{ $settings['appearance']['secondary_color'] ?? '#6B7280' }}">
                                </div>
                                <div class="form-group">
                                    <label>Favicon</label>
                                    <input type="file" class="form-control-file" name="favicon" accept="image/*">
                                    @if($settings['appearance']['favicon'])
                                        <small class="form-text text-muted">Current: {{ $settings['appearance']['favicon'] }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Footer Text</label>
                                    <input type="text" class="form-control" name="footer_text" value="{{ $settings['appearance']['footer_text'] ?? '© 2024 Salon Management System. All rights reserved.' }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Appearance Settings</button>
                    </form>
                </div>

                <!-- Integrations Settings -->
                <div class="tab-pane fade" id="integrations" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'integrations') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Google Analytics ID</label>
                                    <input type="text" class="form-control" name="google_analytics_id" value="{{ $settings['integrations']['google_analytics_id'] ?? '' }}" placeholder="GA-XXXXXXXXX-X">
                                </div>
                                <div class="form-group">
                                    <label>Facebook Pixel ID</label>
                                    <input type="text" class="form-control" name="facebook_pixel_id" value="{{ $settings['integrations']['facebook_pixel_id'] ?? '' }}" placeholder="XXXXXXXXXX">
                                </div>
                                <div class="form-group">
                                    <label>Google Maps API Key</label>
                                    <input type="text" class="form-control" name="google_maps_api_key" value="{{ $settings['integrations']['google_maps_api_key'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Social Media Links</label>
                                    <textarea class="form-control" name="social_media_links" rows="4" placeholder="Enter social media URLs, one per line">{{ is_array($settings['integrations']['social_media_links'] ?? []) ? implode("\n", $settings['integrations']['social_media_links']) : '' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Third Party Scripts</label>
                                    <textarea class="form-control" name="third_party_scripts" rows="4" placeholder="Enter HTML/JavaScript code">{{ $settings['integrations']['third_party_scripts'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Integration Settings</button>
                    </form>
                </div>

                <!-- Backup Settings -->
                <div class="tab-pane fade" id="backup" role="tabpanel">
                    <form method="POST" action="{{ route('admin.settings.update-group', 'backup') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="auto_backup" name="auto_backup" value="1" {{ ($settings['backup']['auto_backup'] ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="auto_backup">Enable Auto Backup</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Backup Frequency</label>
                                    <select class="form-control" name="backup_frequency">
                                        <option value="daily" {{ ($settings['backup']['backup_frequency'] ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ ($settings['backup']['backup_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ ($settings['backup']['backup_frequency'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Backup Retention (days)</label>
                                    <input type="number" class="form-control" name="backup_retention" value="{{ $settings['backup']['backup_retention'] ?? 30 }}" min="7" max="365">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="backup_notifications" name="backup_notifications" value="1" {{ ($settings['backup']['backup_notifications'] ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="backup_notifications">Backup Notifications</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Backup Storage</label>
                                    <select class="form-control" name="backup_storage">
                                        <option value="local" {{ ($settings['backup']['backup_storage'] ?? 'local') == 'local' ? 'selected' : '' }}>Local</option>
                                        <option value="s3" {{ ($settings['backup']['backup_storage'] ?? '') == 's3' ? 'selected' : '' }}>Amazon S3</option>
                                        <option value="dropbox" {{ ($settings['backup']['backup_storage'] ?? '') == 'dropbox' ? 'selected' : '' }}>Dropbox</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-success btn-block" onclick="createBackup()">
                                        <i class="fas fa-download"></i> Create Manual Backup
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Backup Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Tab functionality and smooth scrolling
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#settingsTabs a'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
    
    // Smooth scroll to active tab if it's not visible
    function scrollToActiveTab() {
        const activeTab = document.querySelector('#settingsTabs .nav-link.active');
        if (activeTab) {
            const container = document.querySelector('.settings-tabs-container');
            const tabLeft = activeTab.offsetLeft;
            const containerWidth = container.offsetWidth;
            const scrollLeft = tabLeft - (containerWidth / 2) + (activeTab.offsetWidth / 2);
            
            container.scrollTo({
                left: scrollLeft,
                behavior: 'smooth'
            });
        }
    }
    
    // Scroll to active tab on page load
    setTimeout(scrollToActiveTab, 100);
    
    // Scroll to active tab when tab changes
    document.querySelectorAll('#settingsTabs .nav-link').forEach(tab => {
        tab.addEventListener('shown.bs.tab', scrollToActiveTab);
    });
});

// System maintenance functions
function clearCache() {
    if (confirm('Are you sure you want to clear all caches?')) {
        fetch('{{ route("admin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache cleared successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing cache.');
        });
    }
}

function optimizeDatabase() {
    if (confirm('Are you sure you want to optimize the database?')) {
        fetch('{{ route("admin.settings.optimize-database") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Database optimized successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while optimizing database.');
        });
    }
}

function toggleMaintenanceMode() {
    if (confirm('Are you sure you want to toggle maintenance mode?')) {
        fetch('{{ route("admin.settings.toggle-maintenance") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while toggling maintenance mode.');
        });
    }
}

function runSystemCheck() {
    fetch('{{ route("admin.settings.system-check") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let message = 'System Check Results:\n\n';
            Object.entries(data.checks).forEach(([key, check]) => {
                message += `${key.charAt(0).toUpperCase() + key.slice(1)}: ${check.status} - ${check.message}\n`;
            });
            message += `\nOverall Status: ${data.overall_status}`;
            alert(message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while running system check.');
    });
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all system logs?')) {
        fetch('{{ route("admin.settings.clear-logs") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Logs cleared successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing logs.');
        });
    }
}

function createBackup() {
    if (confirm('Are you sure you want to create a manual backup?')) {
        fetch('{{ route("admin.settings.create-backup") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup created successfully! Filename: ' + data.filename);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating backup.');
        });
    }
}
</script>
@endpush 