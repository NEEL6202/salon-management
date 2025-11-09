@extends('layouts.modern')

@section('title', 'Add New Employee')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Add New Employee</h1>
                <a href="{{ route('salon.employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Employees
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Employee Information</h5>
                </div>
                <div class="card-body">
                    @if($errors->has('limit'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $errors->first('limit') }}
                        </div>
                    @endif

                    <form action="{{ route('salon.employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                   id="avatar" name="avatar" accept="image/*">
                            <div class="form-text">Upload a profile picture (JPEG, PNG, JPG, GIF up to 2MB)</div>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Subscription Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Subscription Information</h5>
                </div>
                <div class="card-body">
                    @php
                        $salon = Auth::user()->salon;
                        $currentEmployees = $salon->users()->whereHas('roles', function($q) {
                            $q->whereIn('name', ['manager', 'employee']);
                        })->count();
                        $maxEmployees = $salon->subscriptionPlan->max_employees ?? 1;
                    @endphp
                    
                    <div class="mb-3">
                        <h6>Current Plan: {{ $salon->subscriptionPlan->name }}</h6>
                        <p class="text-muted mb-0">{{ $salon->subscriptionPlan->description }}</p>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $currentEmployees }}</h4>
                                <small class="text-muted">Current Employees</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">{{ $maxEmployees }}</h4>
                                <small class="text-muted">Maximum Allowed</small>
                            </div>
                        </div>
                    </div>

                    @if($currentEmployees >= $maxEmployees)
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Employee Limit Reached!</strong><br>
                            You've reached the maximum number of employees allowed by your current subscription plan.
                            <a href="#" class="alert-link">Upgrade your plan</a> to add more employees.
                        </div>
                    @else
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            You can add <strong>{{ $maxEmployees - $currentEmployees }}</strong> more employee(s) with your current plan.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Role Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Role Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-user-tie text-info"></i> Manager</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Manage appointments</li>
                            <li>• Manage services & products</li>
                            <li>• View reports & analytics</li>
                            <li>• Manage other employees</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-user text-secondary"></i> Employee</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• View appointments</li>
                            <li>• Update appointment status</li>
                            <li>• View services & products</li>
                            <li>• Basic reporting access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    function checkPasswordMatch() {
        if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    avatarInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add preview functionality here if needed
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush 
