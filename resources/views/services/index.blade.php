@extends('layouts.modern')

@section('title', 'Services')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Services</h1>
        <p class="page-subtitle">Manage your salon services</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Service
        </a>
    </div>
</div>

@if($services->count() > 0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>
                                                @if($service->image)
                                                    <img src="{{ Storage::url($service->image) }}" 
                                                         alt="{{ $service->name }}" 
                                                         class="rounded" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-white"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $service->name }}</div>
                                                    <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($service->category) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">${{ number_format($service->price, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $service->formatted_duration }}</span>
                                            </td>
                                            <td>
                                                @if($service->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('salon.services.show', $service) }}" 
                                                       class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('salon.services.edit', $service) }}" 
                                                       class="btn btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('salon.services.destroy', $service) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to delete this service?')"
                                                          style="display: inline;">
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
    </div>
    
    @if($services->hasPages())
    <div class="card-footer">
        {{ $services->links() }}
    </div>
    @endif
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-concierge-bell fa-4x text-muted mb-3"></i>
        <h4>No Services Found</h4>
        <p class="text-muted">You haven't added any services yet. Start by adding your first service.</p>
        <a href="{{ route('salon.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Your First Service
        </a>
    </div>
</div>
@endif
@endsection 
