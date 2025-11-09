@extends('layouts.modern')

@section('title', 'Products')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Products</h1>
        <p class="page-subtitle">Manage your salon products</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>
</div>

@if($products->count() > 0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                @if($product->image)
                                                    <img src="{{ Storage::url($product->image) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="rounded" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-box text-white"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $product->name }}</div>
                                                    <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $product->category->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $product->sku ?: 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                            </td>
                                            <td>
                                                @if($product->stock_quantity <= 0)
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($product->stock_quantity <= ($product->min_stock_level ?? 5))
                                                    <span class="badge bg-warning">Low Stock ({{ $product->stock_quantity }})</span>
                                                @else
                                                    <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('salon.products.show', $product) }}" 
                                                       class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('salon.products.edit', $product) }}" 
                                                       class="btn btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('salon.products.destroy', $product) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to delete this product?')"
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
    
    @if($products->hasPages())
    <div class="card-footer">
        {{ $products->links() }}
    </div>
    @endif
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-box fa-4x text-muted mb-3"></i>
        <h4>No Products Found</h4>
        <p class="text-muted">You haven't added any products yet. Start by adding your first product.</p>
        <a href="{{ route('salon.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Your First Product
        </a>
    </div>
</div>
@endif
@endsection 

