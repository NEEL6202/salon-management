@extends('layouts.app')

@section('title', 'Edit ' . $salon->name . ' - Admin Dashboard')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Salon</h1>
            <p class="text-muted">Update salon information</p>
        </div>
        <div>
            <a href="{{ route('admin.salons.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Salons
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Salon Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.salons.update', $salon) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Salon Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $salon->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $salon->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $salon->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                           id="website" name="website" value="{{ old('website', $salon->website) }}">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $salon->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                           id="address" name="address" value="{{ old('address', $salon->address) }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $salon->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                           id="state" name="state" value="{{ old('state', $salon->state) }}">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $salon->postal_code) }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                           id="country" name="country" value="{{ old('country', $salon->country) }}">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subscription_plan_id" class="form-label">Subscription Plan</label>
                            <select class="form-select @error('subscription_plan_id') is-invalid @enderror" 
                                    id="subscription_plan_id" name="subscription_plan_id">
                                <option value="">Free Trial</option>
                                @foreach($subscriptionPlans as $plan)
                                    <option value="{{ $plan->id }}" 
                                            {{ old('subscription_plan_id', $salon->subscription_plan_id) == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ${{ number_format($plan->price, 2) }}/month
                                    </option>
                                @endforeach
                            </select>
                            @error('subscription_plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    @if($salon->logo)
                                        <div class="mb-2">
                                            <img src="{{ $salon->logo_url }}" alt="Current Logo" class="img-thumbnail" style="max-width: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    <div class="form-text">Upload a new logo image (max 2MB)</div>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="banner" class="form-label">Banner</label>
                                    @if($salon->banner)
                                        <div class="mb-2">
                                            <img src="{{ $salon->banner_url }}" alt="Current Banner" class="img-thumbnail" style="max-width: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('banner') is-invalid @enderror" 
                                           id="banner" name="banner" accept="image/*">
                                    <div class="form-text">Upload a new banner image (max 2MB)</div>
                                    @error('banner')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.salons.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Salon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Salon Status</h6>
                        <p class="mb-0">
                            @if($salon->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($salon->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6>Current Plan</h6>
                        <p class="mb-0">
                            @if($salon->subscriptionPlan)
                                <span class="badge badge-primary">{{ $salon->subscriptionPlan->name }}</span>
                            @else
                                <span class="badge badge-secondary">Free Trial</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6>Created</h6>
                        <p class="mb-0">{{ $salon->created_at ? $salon->created_at->format('M d, Y') : 'Unknown' }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Last Updated</h6>
                        <p class="mb-0">{{ $salon->updated_at ? $salon->updated_at->format('M d, Y') : 'Unknown' }}</p>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Help</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Required Fields</h6>
                        <p class="text-muted small">Fields marked with * are required to update the salon.</p>
                    </div>
                    <div class="mb-3">
                        <h6>Logo & Banner</h6>
                        <p class="text-muted small">Upload new images to replace the current ones. Leave empty to keep existing images.</p>
                    </div>
                    <div class="mb-3">
                        <h6>Subscription Plan</h6>
                        <p class="text-muted small">Change the subscription plan or leave empty for free trial.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview image before upload
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview functionality here
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('banner').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview functionality here
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection 