@extends('layouts.app')

@section('title', 'Create Subscription Plan - Admin Dashboard')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create Subscription Plan</h1>
            <p class="text-muted">Add a new subscription plan for salons</p>
        </div>
        <div>
            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Plans
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Plan Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Plan Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}" readonly>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Auto-generated from plan name</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_cycle" class="form-label">Billing Cycle *</label>
                                    <select class="form-select @error('billing_cycle') is-invalid @enderror" id="billing_cycle" name="billing_cycle" required>
                                        <option value="">Select Billing Cycle</option>
                                        <option value="monthly" {{ old('billing_cycle') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('billing_cycle') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    @error('billing_cycle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_employees" class="form-label">Max Employees *</label>
                                    <input type="number" class="form-control @error('max_employees') is-invalid @enderror" 
                                           id="max_employees" name="max_employees" value="{{ old('max_employees', 5) }}" min="1" required>
                                    @error('max_employees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_services" class="form-label">Max Services *</label>
                                    <input type="number" class="form-control @error('max_services') is-invalid @enderror" 
                                           id="max_services" name="max_services" value="{{ old('max_services', 20) }}" min="1" required>
                                    @error('max_services')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_products" class="form-label">Max Products *</label>
                                    <input type="number" class="form-control @error('max_products') is-invalid @enderror" 
                                           id="max_products" name="max_products" value="{{ old('max_products', 50) }}" min="1" required>
                                    @error('max_products')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="features" class="form-label">Features</label>
                            <div id="features-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="features[]" placeholder="Enter a feature">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addFeature()">
                                <i class="fas fa-plus me-2"></i>Add Feature
                            </button>
                            <div class="form-text">Add features included in this plan</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Plan
                                        </label>
                                    </div>
                                    <div class="form-text">Make this plan available for subscription</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_popular" name="is_popular" value="1" 
                                               {{ old('is_popular') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_popular">
                                            Popular Plan
                                        </label>
                                    </div>
                                    <div class="form-text">Mark as recommended plan</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Preview</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 id="preview-name">Plan Name</h4>
                        <h2 class="text-primary" id="preview-price">$0.00</h2>
                        <p class="text-muted" id="preview-cycle">Billing Cycle</p>
                    </div>
                    
                    <ul class="list-unstyled" id="preview-features">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <span id="preview-employees">0</span> Employees
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <span id="preview-services">0</span> Services
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <span id="preview-products">0</span> Products
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});

// Add feature field
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="Enter a feature">
        <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
            <i class="fas fa-minus"></i>
        </button>
    `;
    container.appendChild(div);
}

// Remove feature field
function removeFeature(button) {
    button.parentElement.remove();
}

// Live preview
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');
    const cycleInput = document.getElementById('billing_cycle');
    const employeesInput = document.getElementById('max_employees');
    const servicesInput = document.getElementById('max_services');
    const productsInput = document.getElementById('max_products');

    function updatePreview() {
        document.getElementById('preview-name').textContent = nameInput.value || 'Plan Name';
        document.getElementById('preview-price').textContent = '$' + (parseFloat(priceInput.value) || 0).toFixed(2);
        document.getElementById('preview-cycle').textContent = cycleInput.value || 'Billing Cycle';
        document.getElementById('preview-employees').textContent = employeesInput.value || 0;
        document.getElementById('preview-services').textContent = servicesInput.value || 0;
        document.getElementById('preview-products').textContent = productsInput.value || 0;
    }

    [nameInput, priceInput, cycleInput, employeesInput, servicesInput, productsInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    updatePreview();
});
</script>
@endpush 