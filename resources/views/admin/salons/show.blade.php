@extends('layouts.modern')

@section('title', $salon->name . ' - Salon Details')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $salon->name ?? 'Salon Details' }}</h1>
            <p class="text-muted">Detailed information about this salon</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.salons.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Salons
            </a>
            <a href="{{ route('admin.salons.edit', $salon) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Salon
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Salon Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Salon Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Salon Name</label>
                                <p class="mb-0">{{ $salon->name ?? 'Not provided' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="mb-0">{{ $salon->email ?? 'Not provided' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <p class="mb-0">{{ $salon->phone ?? 'Not provided' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Website</label>
                                <p class="mb-0">
                                    @if($salon->website)
                                        <a href="{{ $salon->website }}" target="_blank">{{ $salon->website }}</a>
                                    @else
                                        Not provided
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <p class="mb-0">
                                    @if($salon->address)
                                        {{ $salon->address }}<br>
                                        {{ $salon->city ?? '' }}, {{ $salon->state ?? '' }} {{ $salon->postal_code ?? '' }}<br>
                                        {{ $salon->country ?? '' }}
                                    @else
                                        Not provided
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
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
                                <label class="form-label fw-bold">Created</label>
                                <p class="mb-0">{{ $salon->created_at ? $salon->created_at->format('M d, Y \a\t g:i A') : 'Unknown' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($salon->description)
                    <div class="mt-3">
                        <label class="form-label fw-bold">Description</label>
                        <p class="mb-0">{{ $salon->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Subscription Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Subscription Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Plan</label>
                                <p class="mb-0">
                                    @if($salon->subscriptionPlan)
                                        <span class="badge badge-primary">{{ $salon->subscriptionPlan->name }}</span>
                                    @else
                                        <span class="badge badge-secondary">Free Trial</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trial Ends</label>
                                <p class="mb-0">
                                    @if($salon->trial_ends_at)
                                        {{ $salon->trial_ends_at->format('M d, Y') }}
                                        @if($salon->trial_ends_at->isFuture())
                                            <span class="badge badge-info ms-2">Active</span>
                                        @else
                                            <span class="badge badge-warning ms-2">Expired</span>
                                        @endif
                                    @else
                                        Not applicable
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Subscription Ends</label>
                                <p class="mb-0">
                                    @if($salon->subscription_ends_at)
                                        {{ $salon->subscription_ends_at->format('M d, Y') }}
                                        @if($salon->subscription_ends_at->isFuture())
                                            <span class="badge badge-success ms-2">Active</span>
                                        @else
                                            <span class="badge badge-danger ms-2">Expired</span>
                                        @endif
                                    @else
                                        Not applicable
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary">{{ $salon->users()->count() }}</h3>
                            <p class="text-muted mb-0">Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success">{{ $salon->services()->count() }}</h3>
                            <p class="text-muted mb-0">Services</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-warning">{{ $salon->products()->count() }}</h3>
                            <p class="text-muted mb-0">Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-info">{{ $salon->appointments()->count() }}</h3>
                            <p class="text-muted mb-0">Appointments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Salon Logo -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Salon Logo</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $salon->logo_url ?? asset('media/images/placeholder.svg') }}" 
                         alt="Salon Logo" 
                         class="img-fluid rounded" 
                         style="max-width: 200px;">
                </div>
            </div>

            <!-- Owner Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Owner Information</h5>
                </div>
                <div class="card-body">
                    @if($salon->owner)
                        <div class="text-center mb-3">
                            <img src="{{ $salon->owner->avatar_url ?? asset('media/images/placeholder.svg') }}" 
                                 alt="Owner Avatar" 
                                 class="rounded-circle" 
                                 width="80" 
                                 height="80">
                        </div>
                        <div class="text-center">
                            <h6 class="mb-1">{{ $salon->owner->name ?? 'Unknown' }}</h6>
                            <p class="text-muted mb-2">{{ $salon->owner->email ?? 'No email' }}</p>
                            <p class="text-muted mb-0">{{ $salon->owner->phone ?? 'No phone' }}</p>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-user fa-3x mb-3"></i>
                            <p>No owner assigned</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.salons.edit', $salon) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Salon
                        </a>
                        <button type="button" class="btn btn-danger" onclick="deleteSalon({{ $salon->id }})">
                            <i class="fas fa-trash me-2"></i>Delete Salon
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this salon? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.salons.destroy', $salon) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Salon</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteSalon(salonId) {
    if (confirm('Are you sure you want to delete this salon? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/salons/${salonId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection 