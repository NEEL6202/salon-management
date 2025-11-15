@extends('layouts.modern')

@section('title', 'Gift Card Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Gift Card Details</h1>
        <p class="page-subtitle">View details for gift card {{ $giftCard->code }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.gift-cards.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Gift Cards
        </a>
        <a href="{{ route('salon.gift-cards.edit', $giftCard) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Gift Card
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Gift Card Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Code</h6>
                        <p class="text-muted">{{ $giftCard->code }}</p>
                        
                        <h6>Initial Amount</h6>
                        <p class="text-muted">${{ number_format($giftCard->initial_amount, 2) }}</p>
                        
                        <h6>Current Balance</h6>
                        <p class="text-muted">${{ number_format($giftCard->balance, 2) }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Created By</h6>
                        <p class="text-muted">{{ $giftCard->createdBy->name ?? 'System' }}</p>
                        
                        <h6>Assigned Customer</h6>
                        <p class="text-muted">
                            @if($giftCard->customer)
                                {{ $giftCard->customer->name }}<br>
                                <small>{{ $giftCard->customer->email }}</small>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                        
                        <h6>Status</h6>
                        <p>
                            @if($giftCard->isRedeemed())
                                <span class="badge bg-success">Redeemed</span>
                                <small class="d-block text-muted">On {{ $giftCard->redeemed_at->format('M d, Y g:i A') }}</small>
                            @elseif($giftCard->isActive())
                                <span class="badge bg-primary">Active</span>
                            @elseif($giftCard->isExpired())
                                <span class="badge bg-danger">Expired</span>
                                <small class="d-block text-muted">On {{ $giftCard->expires_at->format('M d, Y') }}</small>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <hr>
                
                <h6>Expiration</h6>
                <p class="text-muted">
                    @if($giftCard->expires_at)
                        {{ $giftCard->expires_at->format('M d, Y') }}
                        @if($giftCard->isExpired())
                            <span class="badge bg-danger">Expired</span>
                        @endif
                    @else
                        Never expires
                    @endif
                </p>
                
                @if($giftCard->message)
                <h6>Personal Message</h6>
                <p class="text-muted">{{ $giftCard->message }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Gift Card Preview</h5>
            </div>
            <div class="card-body">
                <div class="gift-card-preview">
                    <div class="gift-card-header">
                        <h6>{{ $giftCard->salon->name }}</h6>
                    </div>
                    <div class="gift-card-amount">
                        ${{ number_format($giftCard->balance, 2) }}
                    </div>
                    <div class="gift-card-code">
                        Code: {{ $giftCard->code }}
                    </div>
                    <div class="gift-card-expires">
                        Expires: {{ $giftCard->expires_at ? $giftCard->expires_at->format('M d, Y') : 'Never' }}
                    </div>
                    @if($giftCard->message)
                    <div class="gift-card-message">
                        {{ Str::limit($giftCard->message, 100) }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($giftCard->balance > 0 && !$giftCard->isExpired())
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Redeem Gift Card</h5>
    </div>
    <div class="card-body">
        <form id="redeemForm">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Amount to Redeem ($)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" max="{{ $giftCard->balance }}" required>
                        <div class="form-text">Available balance: ${{ number_format($giftCard->balance, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">For Order/Appointment #</label>
                        <input type="text" name="reference" class="form-control" placeholder="Order or appointment number">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-gift"></i> Apply to Order
            </button>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Gift Card History</h5>
    </div>
    <div class="card-body">
        @if($giftCard->transactions->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $giftCard->created_at->format('M d, Y g:i A') }}</td>
                        <td class="text-success">+${{ number_format($giftCard->initial_amount, 2) }}</td>
                        <td>Created</td>
                        <td>Initial balance</td>
                    </tr>
                    @if($giftCard->isRedeemed())
                    <tr>
                        <td>{{ $giftCard->redeemed_at->format('M d, Y g:i A') }}</td>
                        <td class="text-danger">-${{ number_format($giftCard->initial_amount - $giftCard->balance, 2) }}</td>
                        <td>Redeemed</td>
                        <td>Full redemption</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted text-center mb-0">No transaction history available</p>
        @endif
    </div>
</div>

<script>
document.getElementById('redeemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('code', '{{ $giftCard->code }}');
    
    fetch('{{ route("salon.gift-cards.apply") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Gift card applied successfully! Amount deducted: $' + data.deducted_amount.toFixed(2));
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while applying the gift card.');
    });
});
</script>

<style>
.gift-card-preview {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    color: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.gift-card-header h6 {
    margin: 0;
    font-weight: bold;
    border-bottom: 1px dashed rgba(255,255,255,0.5);
    padding-bottom: 10px;
}

.gift-card-amount {
    font-size: 2rem;
    font-weight: bold;
    margin: 15px 0;
}

.gift-card-code {
    font-family: monospace;
    background: rgba(0,0,0,0.2);
    padding: 5px 10px;
    border-radius: 5px;
    margin: 10px 0;
    font-size: 0.9rem;
}

.gift-card-expires {
    font-size: 0.9rem;
    margin: 10px 0;
}

.gift-card-message {
    font-size: 0.8rem;
    font-style: italic;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed rgba(255,255,255,0.5);
}
</style>
@endsection