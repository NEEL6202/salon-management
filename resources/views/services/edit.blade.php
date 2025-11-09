@extends('layouts.modern')

@section('title', 'Edit Service')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit Service</h5>
                        <a href="{{ route('salon.services.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Services
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('salon.services.update', $service) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Service Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $service->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category *</label>
                                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="hair" {{ old('category', $service->category) === 'hair' ? 'selected' : '' }}>Hair</option>
                                            <option value="nails" {{ old('category', $service->category) === 'nails' ? 'selected' : '' }}>Nails</option>
                                            <option value="facial" {{ old('category', $service->category) === 'facial' ? 'selected' : '' }}>Facial</option>
                                            <option value="massage" {{ old('category', $service->category) === 'massage' ? 'selected' : '' }}>Massage</option>
                                            <option value="makeup" {{ old('category', $service->category) === 'makeup' ? 'selected' : '' }}>Makeup</option>
                                            <option value="other" {{ old('category', $service->category) === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Price *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', $service->price) }}" required>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="duration" class="form-label">Duration (minutes) *</label>
                                        <input type="number" min="15" step="15" class="form-control @error('duration') is-invalid @enderror" 
                                               id="duration" name="duration" value="{{ old('duration', $service->duration) }}" required>
                                        @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description', $service->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Service
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Service Image</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            @if($service->image)
                                                <img src="{{ Storage::url($service->image) }}" 
                                                     alt="{{ $service->name }}" 
                                                     class="img-fluid rounded" 
                                                     style="width: 200px; height: 200px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 200px; height: 200px;">
                                                    <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload New Image</label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                   id="image" name="image" accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('salon.services.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image before upload
    const imageInput = document.getElementById('image');
    const imagePreview = document.querySelector('.card-body img, .card-body .bg-light');
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imagePreview.tagName === 'IMG') {
                        imagePreview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.className = 'img-fluid rounded';
                        img.style = 'width: 200px; height: 200px; object-fit: cover;';
                        imagePreview.parentNode.replaceChild(img, imagePreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush 
