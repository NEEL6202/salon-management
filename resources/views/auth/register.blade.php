<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Salon Management Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <style>
        .auth-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 0;
        }
        .auth-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .user-type-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
        }
        .user-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .user-type-card.active {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.875rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="auth-card p-4 p-md-5">
                        <div class="mb-4">
                            <a href="/" class="text-decoration-none d-inline-flex align-items-center mb-3">
                                <i class="fas fa-arrow-left me-2"></i> Back to Home
                            </a>
                            <h3 class="fw-bold mb-2">Create Your Account</h3>
                            <p class="text-muted">Join our salon management platform</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <!-- User Type Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">I want to register as:</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="user-type-card p-3 text-center" data-type="salon_owner">
                                            <i class="fas fa-store fa-3x text-primary mb-2"></i>
                                            <h6 class="fw-bold mb-1">Salon Owner</h6>
                                            <p class="small text-muted mb-0">Manage your salon business</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="user-type-card p-3 text-center active" data-type="customer">
                                            <i class="fas fa-user fa-3x text-success mb-2"></i>
                                            <h6 class="fw-bold mb-1">Customer</h6>
                                            <p class="small text-muted mb-0">Book appointments & shop</p>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="user_type" id="user_type" value="{{ old('user_type', 'customer') }}">
                                @error('user_type')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Personal Information -->
                            <h6 class="fw-bold mb-3">Personal Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Salon Information (for salon owners) -->
                            <div id="salon-info" class="d-none mt-4">
                                <h6 class="fw-bold mb-3">Salon Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="salon_name" class="form-label">Salon Name *</label>
                                        <input type="text" class="form-control @error('salon_name') is-invalid @enderror" 
                                               id="salon_name" name="salon_name" value="{{ old('salon_name') }}">
                                        @error('salon_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="salon_phone" class="form-label">Salon Phone</label>
                                        <input type="tel" class="form-control @error('salon_phone') is-invalid @enderror" 
                                               id="salon_phone" name="salon_phone" value="{{ old('salon_phone') }}">
                                        @error('salon_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="salon_address" class="form-label">Salon Address</label>
                                        <textarea class="form-control @error('salon_address') is-invalid @enderror" 
                                                  id="salon_address" name="salon_address" rows="2">{{ old('salon_address') }}</textarea>
                                        @error('salon_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <h6 class="fw-bold mb-3 mt-4">Security</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password *</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        <button class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                                                type="button" id="togglePassword" style="text-decoration: none;">
                                            <i class="fas fa-eye text-muted"></i>
                                        </button>
                                    </div>
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" required>
                                        <button class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                                                type="button" id="toggleConfirmPassword" style="text-decoration: none;">
                                            <i class="fas fa-eye text-muted"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="form-check mt-4">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                </label>
                                @error('terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-4">
                                <i class="fas fa-user-plus me-2"></i> Create Account
                            </button>
                        </form>

                        <hr class="my-4">
                        <div class="text-center">
                            <p class="mb-0 text-muted">
                                Already have an account? <a href="{{ route('login') }}" class="fw-bold">Sign in here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // User type selection
        document.querySelectorAll('.user-type-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.user-type-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                
                const type = this.dataset.type;
                document.getElementById('user_type').value = type;
                
                const salonInfo = document.getElementById('salon-info');
                if (type === 'salon_owner') {
                    salonInfo.classList.remove('d-none');
                    document.getElementById('salon_name').required = true;
                } else {
                    salonInfo.classList.add('d-none');
                    document.getElementById('salon_name').required = false;
                }
            });
        });

        // Toggle password visibility
        function setupPasswordToggle(toggleId, passwordId) {
            const toggle = document.getElementById(toggleId);
            const password = document.getElementById(passwordId);
            toggle?.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
        setupPasswordToggle('togglePassword', 'password');
        setupPasswordToggle('toggleConfirmPassword', 'password_confirmation');
    </script>
</body>
</html>
