@extends('layouts.modern')

@section('title', 'Customers')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Customers</h1>
        <p class="page-subtitle">Manage your salon customers</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Customer
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." value="{{ $search }}">
            </div>
            <div class="col-md-4">
                <select name="sort_by" class="form-select">
                    <option value="recent" {{ $sortBy == 'recent' ? 'selected' : '' }}>Recently Added</option>
                    <option value="visits" {{ $sortBy == 'visits' ? 'selected' : '' }}>Most Visits</option>
                    <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Total Visits</th>
                        <th>Completed</th>
                        <th>Revenue</th>
                        <th>Last Visit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $customer->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($customer->name) }}" 
                                     alt="{{ $customer->name }}" 
                                     class="rounded-circle" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <strong>{{ $customer->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $customer->email }}</div>
                            <div class="text-muted small">{{ $customer->phone }}</div>
                        </td>
                        <td><span class="badge bg-primary">{{ $customer->total_visits }}</span></td>
                        <td><span class="badge bg-success">{{ $customer->completed_visits }}</span></td>
                        <td><strong>${{ number_format($customer->total_revenue, 2) }}</strong></td>
                        <td>
                            @if($customer->last_visit)
                                {{ $customer->last_visit->format('M d, Y') }}
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('salon.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3 d-block"></i>
                            No customers found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($customers->hasPages())
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
