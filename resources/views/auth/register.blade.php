@extends('layouts.app')

@section('title', 'Register - Salon Management System')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Logo/Brand -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-cut fa-3x text-primary"></i>
                            </div>
                            <h4 class="fw-bold text-dark">Create Your Account</h4>
                            <p class="text-muted">Join our salon management platform</p>
                        </div>

                        <!-- Registration Form -->
                        <form method="POST" action="{{ route('register') }}" data-validate>
                            @csrf
                            
                            <!-- User Type Selection -->
                            <div class="form-group mb-4">
                                <label class="form-label">I want to register as:</label>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 user-type-card" data-type="salon_owner">
                                            <div class="card-body text-center p-3">
                                                <i class="fas fa-store fa-2x text-primary mb-2"></i>
                                                <h6 class="card-title mb-1">Salon Owner</h6>
                                                <p class="card-text small text-muted">Manage your salon business</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 user-type-card" data-type="customer">
                                            <div class="card-body text-center p-3">
                                                <i class="fas fa-user fa-2x text-success mb-2"></i>
                                                <h6 class="card-title mb-1">Customer</h6>
                                                <p class="card-text small text-muted">Book appointments & shop</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="user_type" id="user_type" value="{{ old('user_type', 'customer') }}" required>
                                @error('user_type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Personal Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               required 
                                               autocomplete="name">
                                        @error('name')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               required 
                                               autocomplete="email">
                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               required>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" 
                                                id="gender" 
                                                name="gender" 
                                                required>
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Salon Information (for salon owners) -->
                            <div id="salon-info" class="d-none">
                                <hr class="my-4">
                                <h6 class="mb-3">Salon Information</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="salon_name" class="form-label">Salon Name</label>
                                            <input type="text" 
                                                   class="form-control @error('salon_name') is-invalid @enderror" 
                                                   id="salon_name" 
                                                   name="salon_name" 
                                                   value="{{ old('salon_name') }}">
                                            @error('salon_name')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="salon_phone" class="form-label">Salon Phone</label>
                                            <input type="tel" 
                                                   class="form-control @error('salon_phone') is-invalid @enderror" 
                                                   id="salon_phone" 
                                                   name="salon_phone" 
                                                   value="{{ old('salon_phone') }}">
                                            @error('salon_phone')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="salon_address" class="form-label">Salon Address</label>
                                    <textarea class="form-control @error('salon_address') is-invalid @enderror" 
                                              id="salon_address" 
                                              name="salon_address" 
                                              rows="3">{{ old('salon_address') }}</textarea>
                                    @error('salon_address')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   required 
                                                   autocomplete="new-password">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   required 
                                                   autocomplete="new-password">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    id="toggleConfirmPassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-group mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                           type="checkbox" 
                                           id="terms" 
                                           name="terms" 
                                           required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and 
                                        <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Create Account
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0 text-muted">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        &copy; {{ date('Y') }} Salon Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User type selection
    const userTypeCards = document.querySelectorAll('.user-type-card');
    const userTypeInput = document.getElementById('user_type');
    const salonInfo = document.getElementById('salon-info');
    
    userTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            userTypeCards.forEach(c => c.classList.remove('border-primary', 'bg-light'));
            
            // Add active class to selected card
            this.classList.add('border-primary', 'bg-light');
            
            // Update hidden input
            const type = this.dataset.type;
            userTypeInput.value = type;
            
            // Show/hide salon information
            if (type === 'salon_owner') {
                salonInfo.classList.remove('d-none');
                // Make salon fields required
                document.getElementById('salon_name').required = true;
                document.getElementById('salon_phone').required = true;
                document.getElementById('salon_address').required = true;
            } else {
                salonInfo.classList.add('d-none');
                // Remove required from salon fields
                document.getElementById('salon_name').required = false;
                document.getElementById('salon_phone').required = false;
                document.getElementById('salon_address').required = false;
            }
        });
    });
    
    // Set initial state
    const initialType = userTypeInput.value;
    if (initialType) {
        const initialCard = document.querySelector(`[data-type="${initialType}"]`);
        if (initialCard) {
            initialCard.click();
        }
    }
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    
    function setupPasswordToggle(toggle, field) {
        if (toggle && field) {
            toggle.addEventListener('click', function() {
                const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                field.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    }
    
    setupPasswordToggle(togglePassword, password);
    setupPasswordToggle(toggleConfirmPassword, confirmPassword);
});
</script>
@endpush

@push('styles')
<style>
.user-type-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.user-type-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.user-type-card.border-primary {
    border-color: var(--primary-color) !important;
    background-color: rgba(99, 102, 241, 0.05) !important;
}
</style>
@endpush
@endsection 