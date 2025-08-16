@extends('layouts.app')

@section('title', 'Subscription Plan Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Subscription Plan Details</h1>
                <div>
                    <a href="{{ route('admin.subscription-plans.edit', $subscriptionPlan) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Plan
                    </a>
                    <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Plans
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Plan Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Plan Name</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Slug</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->slug }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <p class="form-control-plaintext">{{ $subscriptionPlan->description ?: 'No description provided' }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Price</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->formatted_price }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Billing Cycle</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->formatted_billing_cycle }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trial Days</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->trial_days }} days</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Max Employees</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->max_employees }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Max Services</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->max_services }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Max Products</label>
                                <p class="form-control-plaintext">{{ $subscriptionPlan->max_products }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    @if($subscriptionPlan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Popular Plan</label>
                                <p class="form-control-plaintext">
                                    @if($subscriptionPlan->is_popular)
                                        <span class="badge bg-warning">Popular</span>
                                    @else
                                        <span class="badge bg-secondary">Standard</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Plan Features</h5>
                </div>
                <div class="card-body">
                    @if($subscriptionPlan->features && count($subscriptionPlan->features) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($subscriptionPlan->features as $feature)
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No features defined for this plan.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Subscription Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary">{{ $subscriptionPlan->subscriptions->count() }}</h4>
                                <small class="text-muted">Total Subscriptions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success">{{ $subscriptionPlan->subscriptions->where('status', 'active')->count() }}</h4>
                                <small class="text-muted">Active Subscriptions</small>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-warning">{{ $subscriptionPlan->subscriptions->where('status', 'trial')->count() }}</h4>
                                <small class="text-muted">Trial Subscriptions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-info">{{ $subscriptionPlan->subscriptions->where('status', 'cancelled')->count() }}</h4>
                                <small class="text-muted">Cancelled</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Subscriptions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Subscriptions</h5>
                </div>
                <div class="card-body">
                    @if($subscriptionPlan->subscriptions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($subscriptionPlan->subscriptions->take(5) as $subscription)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $subscription->salon->name ?? 'Unknown Salon' }}</h6>
                                            <small class="text-muted">{{ $subscription->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trial' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No subscriptions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 