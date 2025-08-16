@extends('layouts.app')

@section('title', 'Edit User - Admin Dashboard')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit User</h1>
            <p class="text-muted">Modify user information and permissions</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
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
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
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
                                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" name="date_of_birth" 
                                   value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave blank to keep current password</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role *</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="super_admin" {{ old('role', $user->roles->first()->name ?? '') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="salon_owner" {{ old('role', $user->roles->first()->name ?? '') === 'salon_owner' ? 'selected' : '' }}>Salon Owner</option>
                                        <option value="manager" {{ old('role', $user->roles->first()->name ?? '') === 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="employee" {{ old('role', $user->roles->first()->name ?? '') === 'employee' ? 'selected' : '' }}>Employee</option>
                                        <option value="customer" {{ old('role', $user->roles->first()->name ?? '') === 'customer' ? 'selected' : '' }}>Customer</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salon_id" class="form-label">Assign to Salon</label>
                                    <select class="form-select @error('salon_id') is-invalid @enderror" id="salon_id" name="salon_id">
                                        <option value="">No Salon (Customer)</option>
                                        @foreach($salons as $salon)
                                            <option value="{{ $salon->id }}" {{ old('salon_id', $user->salon_id) == $salon->id ? 'selected' : '' }}>
                                                {{ $salon->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('salon_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Profile Picture</label>
                            @if($user->avatar)
                                <div class="mb-2">
                                    <img src="{{ $user->avatar_url }}" alt="Current Avatar" class="rounded" width="100">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                   id="avatar" name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a new profile picture (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Details</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $user->avatar_url }}" alt="User Avatar" class="rounded-circle" width="100">
                        <h6 class="mt-2">{{ $user->name }}</h6>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Current Role:</strong>
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                        @endforeach
                    </div>
                    
                    <div class="mb-3">
                        <strong>Salon:</strong>
                        <p class="text-muted">{{ $user->salon->name ?? 'No salon assigned' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Member Since:</strong>
                        <p class="text-muted">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <p class="text-muted">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const salonSelect = document.getElementById('salon_id');
    
    if (role === 'customer') {
        salonSelect.value = '';
        salonSelect.disabled = true;
    } else if (role === 'super_admin') {
        salonSelect.value = '';
        salonSelect.disabled = true;
    } else {
        salonSelect.disabled = false;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const role = document.getElementById('role').value;
    const salonSelect = document.getElementById('salon_id');
    
    if (role === 'customer' || role === 'super_admin') {
        salonSelect.disabled = true;
    }
});
</script>
@endpush 