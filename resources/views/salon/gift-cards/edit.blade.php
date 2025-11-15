@extends('layouts.modern')

@section('title', 'Edit Gift Card')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Gift Card</h1>
        <p class="page-subtitle">Modify your gift card details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.gift-cards.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Gift Cards
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Gift Card Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('salon.gift-cards.update', $giftCard) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control" value="{{ $giftCard->code }}" readonly>
                        <div class="form-text">Gift card code cannot be changed</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Assign to Customer (Optional)</label>
                        <select name="customer_id" class="form-select">
                            <option value="">No customer assigned</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $giftCard->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">You can assign this gift card to a specific customer</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Initial Amount</label>
                                <input type="text" class="form-control" value="${{ number_format($giftCard->initial_amount, 2) }}" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Balance</label>
                                <input type="text" class="form-control" value="${{ number_format($giftCard->balance, 2) }}" readonly>
                                @if($giftCard->isRedeemed())
                                    <div class="form-text text-success">Redeemed on {{ $giftCard->redeemed_at->format('M d, Y') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Expiration</label>
                        <input type="text" class="form-control" 
                               value="{{ $giftCard->expires_at ? $giftCard->expires_at->format('M d, Y') : 'Never' }}" readonly>
                        @if($giftCard->isExpired())
                            <div class="form-text text-danger">This gift card has expired</div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Personal Message</label>
                        <textarea name="message" class="form-control" rows="3" maxlength="500">{{ old('message', $giftCard->message) }}</textarea>
                        <div class="form-text">A personal message to include with the gift card</div>
                        @error('message')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $giftCard->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Uncheck to disable this gift card</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('salon.gift-cards.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Gift Card</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Gift Card Status</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="gift-card-status mb-3">
                        @if($giftCard->isRedeemed())
                            <span class="badge bg-success" style="font-size: 1.2rem;">REDEEMED</span>
                        @elseif($giftCard->isActive())
                            <span class="badge bg-primary" style="font-size: 1.2rem;">ACTIVE</span>
                        @elseif($giftCard->isExpired())
                            <span class="badge bg-danger" style="font-size: 1.2rem;">EXPIRED</span>
                        @else
                            <span class="badge bg-secondary" style="font-size: 1.2rem;">INACTIVE</span>
                        @endif
                    </div>
                    
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
        
        @if(!$giftCard->isRedeemed())
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Once deleted, this gift card cannot be recovered.</p>
                <form action="{{ route('salon.gift-cards.destroy', $giftCard) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gift card? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Delete Gift Card
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

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