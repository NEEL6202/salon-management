@extends('layouts.modern')

@section('title', 'Gift Cards')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Gift Cards</h1>
        <p class="page-subtitle">Manage your salon's gift cards</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.gift-cards.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Gift Card
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by code or customer..." value="{{ $search }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ $status == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="redeemed" {{ $status == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Gift Cards Table -->
<div class="card">
    <div class="card-body">
        @if($giftCards->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($giftCards as $giftCard)
                    <tr>
                        <td>
                            <strong>{{ $giftCard->code }}</strong>
                            @if($giftCard->message)
                                <div class="text-muted small">{{ Str::limit($giftCard->message, 30) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($giftCard->customer)
                                {{ $giftCard->customer->name }}
                            @else
                                <span class="text-muted">No customer</span>
                            @endif
                        </td>
                        <td>${{ number_format($giftCard->initial_amount, 2) }}</td>
                        <td>${{ number_format($giftCard->balance, 2) }}</td>
                        <td>
                            @if($giftCard->expires_at)
                                {{ $giftCard->expires_at->format('M d, Y') }}
                                @if($giftCard->isExpired())
                                    <span class="badge bg-danger">Expired</span>
                                @endif
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            @if($giftCard->isRedeemed())
                                <span class="badge bg-success">Redeemed</span>
                            @elseif($giftCard->isActive())
                                <span class="badge bg-primary">Active</span>
                            @elseif($giftCard->isExpired())
                                <span class="badge bg-danger">Expired</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('salon.gift-cards.show', $giftCard) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('salon.gift-cards.edit', $giftCard) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$giftCard->isRedeemed())
                                <form action="{{ route('salon.gift-cards.destroy', $giftCard) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gift card?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $giftCards->links() }}
        @else
        <div class="text-center py-5">
            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
            <h5>No Gift Cards Found</h5>
            <p class="text-muted">Create your first gift card to start offering them to customers.</p>
            <a href="{{ route('salon.gift-cards.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Gift Card
            </a>
        </div>
        @endif
    </div>
</div>
@endsection