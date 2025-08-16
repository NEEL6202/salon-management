@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Invoices</h4>
                </div>
                <div class="card-body">
                    <!-- Order Invoices -->
                    <div class="mb-4">
                        <h5>Order Invoices</h5>
                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('invoices.order.show', $order->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('invoices.download', ['order', $order->id]) }}" 
                                                       class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <p class="text-muted">No order invoices found.</p>
                        @endif
                    </div>

                    <!-- Subscription Invoices -->
                    @if($subscriptions->count() > 0)
                        <div class="mb-4">
                            <h5>Subscription Invoices</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subscriptions as $subscription)
                                            <tr>
                                                <td>SUB-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $subscription->plan->name }}</td>
                                                <td>${{ number_format($subscription->plan->price, 2) }}</td>
                                                <td>{{ $subscription->starts_at->format('M d, Y') }}</td>
                                                <td>{{ $subscription->ends_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($subscription->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('invoices.subscription.show', $subscription->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('invoices.download', ['subscription', $subscription->id]) }}" 
                                                       class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $subscriptions->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 