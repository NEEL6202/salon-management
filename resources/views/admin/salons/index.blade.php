@extends('layouts.app')

@section('title', 'Manage Salons - Admin Dashboard')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Manage Salons</h1>
            <p class="text-muted">View and manage all registered salons</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.salons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Salon
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.salons.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search salons..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="plan" class="form-select">
                        <option value="">All Plans</option>
                        <option value="trial" {{ request('plan') === 'trial' ? 'selected' : '' }}>Free Trial</option>
                        <option value="starter" {{ request('plan') === 'starter' ? 'selected' : '' }}>Starter</option>
                        <option value="professional" {{ request('plan') === 'professional' ? 'selected' : '' }}>Professional</option>
                        <option value="enterprise" {{ request('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Salons Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Salon</th>
                            <th>Owner</th>
                            <th>Contact</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salons as $salon)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $salon->logo_url ?? asset('media/images/placeholder.svg') }}" 
                                         alt="Logo" 
                                         class="rounded me-3" 
                                         width="50" 
                                         height="50">
                                    <div>
                                        <div class="fw-bold">{{ $salon->name ?? 'Unnamed Salon' }}</div>
                                        <small class="text-muted">{{ $salon->email ?? 'No email' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($salon->owner)
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $salon->owner->avatar_url ?? asset('media/images/placeholder.svg') }}" 
                                             alt="Avatar" 
                                             class="rounded-circle me-2" 
                                             width="32" 
                                             height="32">
                                        <span>{{ $salon->owner->name ?? 'Unknown' }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">No owner assigned</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <div>{{ $salon->phone ?? 'No phone' }}</div>
                                    <small class="text-muted">{{ $salon->city ?? 'No city' }}, {{ $salon->state ?? 'No state' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($salon->subscriptionPlan)
                                    <span class="badge badge-primary">
                                        {{ $salon->subscriptionPlan->name ?? 'Unknown Plan' }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Free Trial</span>
                                @endif
                            </td>
                            <td>
                                @if($salon->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($salon->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <div>{{ $salon->created_at ? $salon->created_at->format('M d, Y') : 'Unknown' }}</div>
                                    <small class="text-muted">{{ $salon->created_at ? $salon->created_at->diffForHumans() : '' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.salons.show', $salon) }}" 
                                       class="btn btn-outline-primary" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.salons.edit', $salon) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit Salon">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            title="Delete Salon"
                                            onclick="deleteSalon({{ $salon->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="mb-3">
                                    <i class="fas fa-store fa-3x text-muted"></i>
                                </div>
                                <h5>No salons found</h5>
                                <p>Get started by adding your first salon.</p>
                                <a href="{{ route('admin.salons.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add New Salon
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($salons->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $salons->links() }}
    </div>
    @endif
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
                <form id="deleteForm" method="POST" style="display: inline;">
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