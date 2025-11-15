@extends('layouts.modern')

@section('title', 'Customer Reviews')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Customer Reviews</h1>
        <p class="page-subtitle">Manage and respond to customer reviews</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.reviews.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Review
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4" id="reviewStats">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" id="totalReviews">0</h3>
                    <p class="stat-label">Total Reviews</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" id="approvedReviews">0</h3>
                    <p class="stat-label">Approved</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" id="pendingReviews">0</h3>
                    <p class="stat-label">Pending</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-info">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" id="averageRating">0.0</h3>
                    <p class="stat-label">Average Rating</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search reviews..." value="{{ $search }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="featured" {{ $status == 'featured' ? 'selected' : '' }}>Featured</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    <option value="5" {{ $rating == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ $rating == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="3" {{ $rating == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="2" {{ $rating == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="1" {{ $rating == '1' ? 'selected' : '' }}>1 Star</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reviews Table -->
<div class="card">
    <div class="card-body">
        @if($reviews->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Review</th>
                        <th>Rating</th>
                        <th>Service/Employee</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $review->customer->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($review->customer->name) }}" 
                                     alt="{{ $review->customer->name }}" 
                                     class="rounded-circle" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <strong>{{ $review->customer->name }}</strong>
                                    @if($review->title)
                                        <div class="text-muted small">{{ Str::limit($review->title, 30) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ Str::limit($review->review, 100) }}</td>
                        <td>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td>
                            @if($review->service)
                                <div><strong>Service:</strong> {{ $review->service->name }}</div>
                            @endif
                            @if($review->employee)
                                <div><strong>Employee:</strong> {{ $review->employee->name }}</div>
                            @endif
                        </td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                            @if($review->is_featured)
                                <span class="badge bg-info">Featured</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('salon.reviews.show', $review) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('salon.reviews.edit', $review) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$review->is_approved)
                                <button type="button" class="btn btn-outline-success approve-review" data-id="{{ $review->id }}">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-outline-info feature-review {{ $review->is_featured ? 'active' : '' }}" 
                                        data-id="{{ $review->id }}">
                                    <i class="fas fa-crown"></i>
                                </button>
                                <form action="{{ route('salon.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $reviews->links() }}
        @else
        <div class="text-center py-5">
            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
            <h5>No Reviews Found</h5>
            <p class="text-muted">There are no customer reviews yet.</p>
            <a href="{{ route('salon.reviews.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Review
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load review stats
    fetchReviewStats();
    
    // Approve review buttons
    document.querySelectorAll('.approve-review').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-id');
            approveReview(reviewId);
        });
    });
    
    // Feature review buttons
    document.querySelectorAll('.feature-review').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-id');
            featureReview(reviewId);
        });
    });
});

function fetchReviewStats() {
    fetch('{{ route("salon.reviews.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalReviews').textContent = data.stats.total_reviews;
                document.getElementById('approvedReviews').textContent = data.stats.approved_reviews;
                document.getElementById('pendingReviews').textContent = data.stats.pending_reviews;
                document.getElementById('averageRating').textContent = data.stats.average_rating;
            }
        })
        .catch(error => console.error('Error fetching stats:', error));
}

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
.stat-card {
    border: none;
    box-shadow: var(--shadow-sm);
}

.stat-card .card-body {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
}

.rating-stars .fas.fa-star,
.rating-stars .far.fa-star {
    font-size: 0.9rem;
}
</style>