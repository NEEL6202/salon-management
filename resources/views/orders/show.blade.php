@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Order #{{ $order->id }}</h1>
        <div>
            <a href="{{ route('salon.orders.edit', $order) }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i> Edit Order
            </a>
            <a href="{{ route('salon.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? $item->service->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($item->item_type) }}</span>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="fw-bold">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Order Status</label>
                        <div class="form-control-plaintext">
                            <span class="badge {{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Customer</label>
                        <div class="form-control-plaintext">
                            {{ $order->customer->name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Order Date</label>
                        <div class="form-control-plaintext">
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Subtotal</label>
                        <div class="form-control-plaintext">${{ number_format($order->subtotal, 2) }}</div>
                    </div>

                    @if($order->tax_amount > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tax</label>
                            <div class="form-control-plaintext">${{ number_format($order->tax_amount, 2) }}</div>
                        </div>
                    @endif

                    @if($order->discount_amount > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Discount</label>
                            <div class="form-control-plaintext text-success">-${{ number_format($order->discount_amount, 2) }}</div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Amount</label>
                        <div class="form-control-plaintext">
                            <span class="fw-bold text-success fs-5">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes</label>
                            <div class="form-control-plaintext">{{ $order->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 