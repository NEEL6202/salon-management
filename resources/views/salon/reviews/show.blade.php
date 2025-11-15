@extends('layouts.modern')

@section('title', 'Review Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Review Details</h1>
        <p class="page-subtitle">View customer review information</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.reviews.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reviews
        </a>
        <a href="{{ route('salon.reviews.edit', $review) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Review
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Review Content</h5>
            </div>
            <div class="card-body">
                <div class="review-header mb-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4>{{ $review->title ?? 'Customer Review' }}</h4>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <img src="{{ $review->customer->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($review->customer->name) }}" 
                                     alt="{{ $review->customer->name }}" 
                                     class="rounded-circle" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">{{ $review->customer->name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="rating-stars mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">{{ $review->rating }}/5 stars</small>
                        </div>
                    </div>
                </div>
                
                <div class="review-content">
                    <p class="lead">{{ $review->review }}</p>
                </div>
                
                @if($review->photos)
                <div class="review-photos mt-4">
                    <h6>Photos</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($review->photos as $photo)
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Review photo" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Review Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <h6>Approval Status</h6>
                        <p>
                            @if($review->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h6>Featured Status</h6>
                        <p>
                            @if($review->is_featured)
                                <span class="badge bg-info">Featured</span>
                            @else
                                <span class="badge bg-secondary">Not Featured</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h6>Source</h6>
                        <p class="text-muted">{{ ucfirst($review->source) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Related Information</h5>
            </div>
            <div class="card-body">
                @if($review->service)
                <div class="mb-3">
                    <h6>Service</h6>
                    <p class="text-muted">{{ $review->service->name }}</p>
                </div>
                @endif
                
                @if($review->employee)
                <div class="mb-3">
                    <h6>Employee</h6>
                    <p class="text-muted">{{ $review->employee->name }}</p>
                </div>
                @endif
                
                @if($review->appointment)
                <div class="mb-0">
                    <h6>Appointment</h6>
                    <p class="text-muted">
                        {{ $review->appointment->service->name }}<br>
                        <small>{{ $review->appointment->appointment_date->format('M d, Y g:i A') }}</small>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Actions</h5>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            @if(!$review->is_approved)
            <button type="button" class="btn btn-success approve-review" data-id="{{ $review->id }}">
                <i class="fas fa-check"></i> Approve Review
            </button>
            @endif
            
            <button type="button" class="btn btn-{{ $review->is_featured ? 'info' : 'outline-info' }} feature-review" data-id="{{ $review->id }}">
                <i class="fas fa-crown"></i> {{ $review->is_featured ? 'Unfeature' : 'Feature' }} Review
            </button>
            
            <a href="{{ route('salon.reviews.edit', $review) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Review
            </a>
            
            <form action="{{ route('salon.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Review
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Approve review button
    document.querySelector('.approve-review')?.addEventListener('click', function() {
        const reviewId = this.getAttribute('data-id');
        approveReview(reviewId);
    });
    
    // Feature review button
    document.querySelector('.feature-review')?.addEventListener('click', function() {
        const reviewId = this.getAttribute('data-id');
        featureReview(reviewId);
    });
});

function approveReview(reviewId) {
    if (!confirm('Are you sure you want to approve this review?')) {
        return;
    }
    
    fetch('{{ url("salon/reviews") }}/' + reviewId + '/approve', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Review approved successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while approving the review.');
    });
}

function featureReview(reviewId) {
    fetch('{{ url("salon/reviews") }}/' + reviewId + '/feature', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while featuring the review.');
    });
}
</script>
@endpush

<style>
.review-header {
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 1rem;
}

.rating-stars .fas.fa-star,
.rating-stars .far.fa-star {
    font-size: 1.2rem;
}

.review-content p {
    font-size: 1.1rem;
    line-height: 1.6;
}
</style>
@endsection