@extends('layouts.modern')

@section('title', 'Edit Subscription Plan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Edit Subscription Plan</h1>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Plans
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscription-plans.update', $subscriptionPlan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $subscriptionPlan->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="slug">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug', $subscriptionPlan->slug) }}">
                                    <div class="form-text">Leave empty to auto-generate from name</div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $subscriptionPlan->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" step="0.01" min="0" 
                                               value="{{ old('price', $subscriptionPlan->price) }}" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="billing_cycle">Billing Cycle <span class="text-danger">*</span></label>
                                    <select class="form-select @error('billing_cycle') is-invalid @enderror" 
                                            id="billing_cycle" name="billing_cycle" required>
                                        <option value="">Select Billing Cycle</option>
                                        <option value="monthly" {{ old('billing_cycle', $subscriptionPlan->billing_cycle) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('billing_cycle', $subscriptionPlan->billing_cycle) === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('billing_cycle', $subscriptionPlan->billing_cycle) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    @error('billing_cycle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="trial_days">Trial Days</label>
                                    <input type="number" class="form-control @error('trial_days') is-invalid @enderror" 
                                           id="trial_days" name="trial_days" min="0" max="90" 
                                           value="{{ old('trial_days', $subscriptionPlan->trial_days) }}">
                                    @error('trial_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="max_employees">Max Employees <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_employees') is-invalid @enderror" 
                                           id="max_employees" name="max_employees" min="1" 
                                           value="{{ old('max_employees', $subscriptionPlan->max_employees) }}" required>
                                    @error('max_employees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="max_services">Max Services <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_services') is-invalid @enderror" 
                                           id="max_services" name="max_services" min="1" 
                                           value="{{ old('max_services', $subscriptionPlan->max_services) }}" required>
                                    @error('max_services')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="max_products">Max Products <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_products') is-invalid @enderror" 
                                           id="max_products" name="max_products" min="1" 
                                           value="{{ old('max_products', $subscriptionPlan->max_products) }}" required>
                                    @error('max_products')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $subscriptionPlan->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Plan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_popular" name="is_popular" 
                                               {{ old('is_popular', $subscriptionPlan->is_popular) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_popular">
                                            Popular Plan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Features</label>
                            <div id="features-container">
                                @if($subscriptionPlan->features && count($subscriptionPlan->features) > 0)
                                    @foreach($subscriptionPlan->features as $index => $feature)
                                        <div class="input-group mb-2 feature-input">
                                            <input type="text" class="form-control" name="features[]" 
                                                   value="{{ $feature }}" placeholder="Enter feature">
                                            <button type="button" class="btn btn-outline-danger remove-feature">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 feature-input">
                                        <input type="text" class="form-control" name="features[]" 
                                               placeholder="Enter feature">
                                        <button type="button" class="btn btn-outline-danger remove-feature">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-feature">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Plan Preview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Preview</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 id="preview-name">{{ $subscriptionPlan->name }}</h4>
                        <div class="h2 text-primary mb-2">
                            $<span id="preview-price">{{ number_format($subscriptionPlan->price, 2) }}</span>
                            <small class="text-muted">/<span id="preview-cycle">{{ ucfirst($subscriptionPlan->billing_cycle) }}</span></small>
                        </div>
                        <p id="preview-description" class="text-muted">{{ $subscriptionPlan->description ?: 'No description' }}</p>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-1" id="preview-employees">{{ $subscriptionPlan->max_employees }}</h6>
                                <small class="text-muted">Employees</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-1" id="preview-services">{{ $subscriptionPlan->max_services }}</h6>
                                <small class="text-muted">Services</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-1" id="preview-products">{{ $subscriptionPlan->max_products }}</h6>
                                <small class="text-muted">Products</small>
                            </div>
                        </div>
                    </div>

                    <div id="preview-features">
                        @if($subscriptionPlan->features && count($subscriptionPlan->features) > 0)
                            <ul class="list-unstyled">
                                @foreach($subscriptionPlan->features as $feature)
                                    <li><i class="fas fa-check text-success me-2"></i>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No features defined</p>
                        @endif
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
    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value) {
            slugInput.value = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }
        updatePreview();
    });

    // Update preview
    function updatePreview() {
        document.getElementById('preview-name').textContent = nameInput.value || 'Plan Name';
        document.getElementById('preview-price').textContent = (document.getElementById('price').value || '0').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('preview-cycle').textContent = document.getElementById('billing_cycle').options[document.getElementById('billing_cycle').selectedIndex]?.text || 'Cycle';
        document.getElementById('preview-description').textContent = document.getElementById('description').value || 'No description';
        document.getElementById('preview-employees').textContent = document.getElementById('max_employees').value || '0';
        document.getElementById('preview-services').textContent = document.getElementById('max_services').value || '0';
        document.getElementById('preview-products').textContent = document.getElementById('max_products').value || '0';
    }

    // Add event listeners for preview updates
    ['price', 'billing_cycle', 'description', 'max_employees', 'max_services', 'max_products'].forEach(id => {
        document.getElementById(id).addEventListener('input', updatePreview);
        document.getElementById(id).addEventListener('change', updatePreview);
    });

    // Feature management
    const featuresContainer = document.getElementById('features-container');
    const addFeatureBtn = document.getElementById('add-feature');

    addFeatureBtn.addEventListener('click', function() {
        const featureInput = document.createElement('div');
        featureInput.className = 'input-group mb-2 feature-input';
        featureInput.innerHTML = `
            <input type="text" class="form-control" name="features[]" placeholder="Enter feature">
            <button type="button" class="btn btn-outline-danger remove-feature">
                <i class="fas fa-trash"></i>
            </button>
        `;
        featuresContainer.appendChild(featureInput);
    });

    featuresContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-feature') || e.target.closest('.remove-feature')) {
            e.target.closest('.feature-input').remove();
        }
    });

    // Initialize preview
    updatePreview();
});
</script>
@endpush 