@extends('layouts.app')

@section('title', 'Salon Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Salon Profile</h5>
                        <a href="{{ route('salon.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($salon)
                        <form action="{{ route('salon.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Salon Name *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $salon->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email', $salon->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Phone Number *</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" value="{{ old('phone', $salon->phone) }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="website" class="form-label">Website</label>
                                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                                   id="website" name="website" value="{{ old('website', $salon->website) }}">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address *</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3" required>{{ old('address', $salon->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4">{{ old('description', $salon->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="opening_hours" class="form-label">Opening Hours</label>
                                        <textarea class="form-control @error('opening_hours') is-invalid @enderror" 
                                                  id="opening_hours" name="opening_hours" rows="3" 
                                                  placeholder="e.g., Monday-Friday: 9:00 AM - 8:00 PM&#10;Saturday: 9:00 AM - 6:00 PM&#10;Sunday: 10:00 AM - 4:00 PM">{{ old('opening_hours', $salon->opening_hours) }}</textarea>
                                        @error('opening_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Salon Logo</h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                @if($salon->logo)
                                                    <img src="{{ Storage::url($salon->logo) }}" 
                                                         alt="{{ $salon->name }}" 
                                                         class="img-fluid rounded" 
                                                         style="max-width: 200px; max-height: 200px;">
                                                @else
                                                    <div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 200px; height: 200px;">
                                                        <i class="fas fa-store text-white" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="logo" class="form-label">Upload Logo</label>
                                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                                       id="logo" name="logo" accept="image/*">
                                                @error('logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF, SVG</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Salon Banner</h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                @if($salon->banner)
                                                    <img src="{{ Storage::url($salon->banner) }}" 
                                                         alt="{{ $salon->name }}" 
                                                         class="img-fluid rounded" 
                                                         style="max-width: 100%; max-height: 150px;">
                                                @else
                                                    <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 100%; height: 150px;">
                                                        <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="banner" class="form-label">Upload Banner</label>
                                                <input type="file" class="form-control @error('banner') is-invalid @enderror" 
                                                       id="banner" name="banner" accept="image/*">
                                                @error('banner')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Salon Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <small class="text-muted">Salon ID:</small>
                                                <div class="fw-bold">{{ $salon->id }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Status:</small>
                                                <div class="fw-bold">
                                                    <span class="badge bg-{{ $salon->status === 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($salon->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Created:</small>
                                                <div class="fw-bold">{{ $salon->created_at->format('M d, Y') }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Last Updated:</small>
                                                <div class="fw-bold">{{ $salon->updated_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('salon.dashboard') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-store fa-3x text-muted mb-3"></i>
                            <h4>No Salon Profile Found</h4>
                            <p class="text-muted">Please contact the administrator to set up your salon profile.</p>
                        </div>
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