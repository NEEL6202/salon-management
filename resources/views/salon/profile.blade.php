@extends('layouts.modern')

@section('title', 'Salon Profile')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Salon Profile</h1>
        <p class="page-subtitle">Manage your salon information and branding</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

@if($salon)
<form action="{{ route('salon.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Main Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Salon Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $salon->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $salon->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $salon->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                                   value="{{ old('website', $salon->website) }}" placeholder="https://">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" required>{{ old('address', $salon->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" placeholder="Tell customers about your salon...">{{ old('description', $salon->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Opening Hours</label>
                            <textarea name="opening_hours" class="form-control @error('opening_hours') is-invalid @enderror" 
                                      rows="3" placeholder="e.g., Mon-Fri: 9:00 AM - 8:00 PM&#10;Sat: 9:00 AM - 6:00 PM&#10;Sun: Closed">{{ old('opening_hours', $salon->opening_hours) }}</textarea>
                            @error('opening_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Branding & Info -->
        <div class="col-md-4">
            <!-- Logo -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Salon Logo</h5>
                </div>
                <div class="card-body text-center">
                    <div class="logo-preview mb-3">
                        @if($salon->logo)
                            <img src="{{ Storage::url($salon->logo) }}" alt="{{ $salon->name }}" class="img-fluid rounded">
                        @else
                            <div class="logo-placeholder">
                                <i class="fas fa-store"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="form-label">Upload Logo</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Max 2MB • PNG, JPG, GIF, SVG</small>
                    </div>
                </div>
            </div>
            
            <!-- Banner -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Salon Banner</h5>
                </div>
                <div class="card-body text-center">
                    <div class="banner-preview mb-3">
                        @if($salon->banner)
                            <img src="{{ Storage::url($salon->banner) }}" alt="{{ $salon->name }}" class="img-fluid rounded">
                        @else
                            <div class="banner-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="form-label">Upload Banner</label>
                        <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                        @error('banner')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Max 2MB • PNG, JPG, GIF</small>
                    </div>
                </div>
            </div>
            
            <!-- Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Salon Details</h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <small class="text-muted">Salon ID</small>
                        <div class="fw-bold">{{ $salon->id }}</div>
                    </div>
                    <div class="info-item">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $salon->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($salon->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <small class="text-muted">Created</small>
                        <div class="fw-bold">{{ $salon->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="info-item">
                        <small class="text-muted">Last Updated</small>
                        <div class="fw-bold">{{ $salon->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('salon.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Profile
        </button>
    </div>
</form>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-store fa-4x text-muted mb-3"></i>
        <h4>No Salon Profile Found</h4>
        <p class="text-muted">Please contact the administrator to set up your salon profile.</p>
    </div>
</div>
@endif

<style>
.logo-preview, .banner-preview {
    position: relative;
    width: 100%;
    min-height: 150px;
    border-radius: 8px;
    overflow: hidden;
}

.logo-preview img {
    max-width: 200px;
    max-height: 200px;
    object-fit: contain;
}

.logo-placeholder, .banner-placeholder {
    width: 100%;
    height: 150px;
    background: var(--bg-secondary);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-tertiary);
    font-size: 3rem;
}

.banner-preview img {
    width: 100%;
    max-height: 150px;
    object-fit: cover;
}

.banner-placeholder {
    height: 120px;
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item small {
    display: block;
    margin-bottom: 0.25rem;
}
</style>
@endsection
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview logo before upload
    const logoInput = document.getElementById('logo');
    const logoPreview = document.querySelector('.card-body img, .card-body .bg-secondary');
    
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (logoPreview.tagName === 'IMG') {
                        logoPreview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.className = 'img-fluid rounded';
                        img.style = 'max-width: 200px; max-height: 200px;';
                        logoPreview.parentNode.replaceChild(img, logoPreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Preview banner before upload
    const bannerInput = document.getElementById('banner');
    const bannerPreview = document.querySelectorAll('.card-body img, .card-body .bg-light')[1];
    
    if (bannerInput) {
        bannerInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (bannerPreview.tagName === 'IMG') {
                        bannerPreview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.className = 'img-fluid rounded';
                        img.style = 'max-width: 100%; max-height: 150px;';
                        bannerPreview.parentNode.replaceChild(img, bannerPreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush 