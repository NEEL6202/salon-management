@extends('layouts.modern')

@section('title', 'Add Customer Review')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Add Customer Review</h1>
        <p class="page-subtitle">Manually add a customer review</p>
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
                <form action="{{ route('salon.reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
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
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
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
                                <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
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
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="255">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Review *</label>
                        <textarea name="review" class="form-control" rows="5" maxlength="1000" required>{{ old('review') }}</textarea>
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
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">
                                Feature this review
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('salon.reviews.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Review Guidelines</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Reviews added here are automatically approved</li>
                    <li>Customers will not be notified of manual reviews</li>
                    <li>Photos will be displayed on the website if enabled</li>
                    <li>Featured reviews appear prominently on your website</li>
                    <li>Rating affects your overall salon rating</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Tips</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Be specific about services and employees</li>
                    <li>Encourage customers to leave their own reviews</li>
                    <li>Respond to negative reviews professionally</li>
                    <li>Highlight positive reviews in marketing</li>
                </ul>
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
</style>
@endsection