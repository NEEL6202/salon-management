@extends('layouts.modern')

@section('title', 'Edit Customer Review')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Customer Review</h1>
        <p class="page-subtitle">Modify customer review details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.reviews.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reviews
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Review Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('salon.reviews.update', $review) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $review->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Service (Optional)</label>
                                <select name="service_id" class="form-select">
                                    <option value="">Select a service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id', $review->service_id) == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Employee (Optional)</label>
                                <select name="employee_id" class="form-select">
                                    <option value="">Select an employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $review->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rating *</label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                <label for="rating{{ $i }}">
                                    @for($j = 1; $j <= 5; $j++)
                                        @if($j <= $i)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</span>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Title (Optional)</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $review->title) }}" maxlength="255">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Review *</label>
                        <textarea name="review" class="form-control" rows="5" maxlength="1000" required>{{ old('review', $review->review) }}</textarea>
                        <div class="form-text">Maximum 1000 characters</div>
                        @error('review')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Photos (Optional, max 5)</label>
                        <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
                        <div class="form-text">Upload up to 5 photos (JPG, PNG, GIF). Maximum 2MB each.</div>
                        @error('photos')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('photos.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        
                        @if($review->photos)
                        <div class="mt-2">
                            <label class="form-label">Current Photos:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($review->photos as $photo)
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Review photo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" value="1" {{ old('is_approved', $review->is_approved) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isApproved">
                                        Approved
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" {{ old('is_featured', $review->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isFeatured">
                                        Featured
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('salon.reviews.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Review Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Posted By</h6>
                    <p class="text-muted">{{ $review->customer->name }}</p>
                    
                    <h6>Date</h6>
                    <p class="text-muted">{{ $review->created_at->format('M d, Y g:i A') }}</p>
                    
                    <h6>Source</h6>
                    <p class="text-muted">{{ ucfirst($review->source) }}</p>
                </div>
                
                <div class="mb-3">
                    <h6>Current Rating</h6>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                </div>
                
                @if($review->service || $review->employee)
                <div class="mb-3">
                    <h6>Related To</h6>
                    @if($review->service)
                    <p class="text-muted mb-1"><strong>Service:</strong> {{ $review->service->name }}</p>
                    @endif
                    @if($review->employee)
                    <p class="text-muted mb-0"><strong>Employee:</strong> {{ $review->employee->name }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Once deleted, this review cannot be recovered.</p>
                <form action="{{ route('salon.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Delete Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.rating-input {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.rating-input label:hover {
    border-color: var(--primary);
    background-color: var(--bg-secondary);
}

.rating-input input[type="radio"]:checked + label {
    border-color: var(--primary);
    background-color: rgba(var(--primary-rgb), 0.1);
}

.rating-input .fas.fa-star,
.rating-input .far.fa-star {
    color: #ffc107;
    font-size: 1.2rem;
    margin-right: 0.5rem;
}

.rating-input .far.fa-star {
    color: var(--text-secondary);
}

.rating-stars .fas.fa-star,
.rating-stars .far.fa-star {
    font-size: 1.2rem;
}
</style>
@endsection