@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Product Details</h5>
                        <div>
                            <a href="{{ route('salon.products.edit', $product) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit Product
                            </a>
                            <a href="{{ route('salon.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Products
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Product Name</label>
                                    <div class="form-control-plaintext">{{ $product->name }}</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">SKU</label>
                                    <div class="form-control-plaintext">{{ $product->sku ?: 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Category</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-info">{{ $product->category->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Unit</label>
                                    <div class="form-control-plaintext">{{ $product->unit ?: 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <div class="form-control-plaintext">
                                        <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Cost Price</label>
                                    <div class="form-control-plaintext">
                                        <span class="fw-bold text-info">${{ number_format($product->cost_price, 2) }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Profit Margin</label>
                                    <div class="form-control-plaintext">
                                        <span class="fw-bold text-warning">{{ number_format($product->profit_margin, 1) }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stock Quantity</label>
                                    <div class="form-control-plaintext">
                                        @if($product->stock_quantity <= 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->stock_quantity <= ($product->min_stock_level ?? 5))
                                            <span class="badge bg-warning">Low Stock ({{ $product->stock_quantity }})</span>
                                        @else
                                            <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Minimum Stock Level</label>
                                    <div class="form-control-plaintext">{{ $product->min_stock_level ?: 'Not set' }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <div class="form-control-plaintext">
                                    {{ $product->description ?: 'No description provided.' }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <div class="form-control-plaintext">
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Featured</label>
                                    <div class="form-control-plaintext">
                                        @if($product->is_featured)
                                            <span class="badge bg-primary">Featured</span>
                                        @else
                                            <span class="badge bg-secondary">Not Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Product Image</h6>
                                </div>
                                <div class="card-body text-center">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}" 
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
                                    <h6 class="card-title mb-0">Product Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Product ID:</small>
                                        <div class="fw-bold">{{ $product->id }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Created:</small>
                                        <div class="fw-bold">{{ $product->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Last Updated:</small>
                                        <div class="fw-bold">{{ $product->updated_at->format('M d, Y') }}</div>
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