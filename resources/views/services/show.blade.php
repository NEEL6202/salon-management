@extends('layouts.modern')

@section('title', $service->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Service Details</h5>
                        <div>
                            <a href="{{ route('salon.services.edit', $service) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit Service
                            </a>
                            <a href="{{ route('salon.services.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Services
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Service Name</label>
                                    <div class="form-control-plaintext">{{ $service->name }}</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Category</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-info">{{ ucfirst($service->category) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Price</label>
                                    <div class="form-control-plaintext">
                                        <span class="fw-bold text-success">${{ number_format($service->price, 2) }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Duration</label>
                                    <div class="form-control-plaintext">{{ $service->formatted_duration }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <div class="form-control-plaintext">
                                    {{ $service->description ?: 'No description provided.' }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <div class="form-control-plaintext">
                                    @if($service->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Service Image</h6>
                                </div>
                                <div class="card-body text-center">
                                    @if($service->image)
                                        <img src="{{ Storage::url($service->image) }}" 
                                             alt="{{ $service->name }}" 
                                             class="img-fluid rounded" 
                                             style="max-width: 100%; max-height: 300px;">
                                    @else
                                        <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" 
                                             style="width: 200px; height: 200px;">
                                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Service Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Service ID:</small>
                                        <div class="fw-bold">{{ $service->id }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Created:</small>
                                        <div class="fw-bold">{{ $service->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Last Updated:</small>
                                        <div class="fw-bold">{{ $service->updated_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
