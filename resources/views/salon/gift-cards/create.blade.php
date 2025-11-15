@extends('layouts.modern')

@section('title', 'Create Gift Card')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Create Gift Card</h1>
        <p class="page-subtitle">Generate a new gift card for your customers</p>
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
                <form action="{{ route('salon.gift-cards.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Assign to Customer (Optional)</label>
                        <select name="customer_id" class="form-select">
                            <option value="">Select a customer (optional)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">You can assign this gift card to a specific customer</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Amount ($)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="1" max="1000" value="{{ old('amount', 50) }}" required>
                        <div class="form-text">The monetary value of the gift card</div>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Expiration (Days from now)</label>
                        <input type="number" name="expires_in_days" class="form-control" min="1" max="365" value="{{ old('expires_in_days', 365) }}">
                        <div class="form-text">Leave blank for no expiration</div>
                        @error('expires_in_days')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Personal Message (Optional)</label>
                        <textarea name="message" class="form-control" rows="3" maxlength="500">{{ old('message') }}</textarea>
                        <div class="form-text">A personal message to include with the gift card</div>
                        @error('message')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('salon.gift-cards.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Gift Card</button>
                    </div>
                </form>
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
                        <h6>{{ $salon->name }}</h6>
                    </div>
                    <div class="gift-card-amount">
                        $<span id="amountPreview">50.00</span>
                    </div>
                    <div class="gift-card-code">
                        Code: <span id="codePreview">XXXXXXXXXXXX</span>
                    </div>
                    <div class="gift-card-expires">
                        Expires: <span id="expiresPreview">Never</span>
                    </div>
                    <div class="gift-card-message">
                        <span id="messagePreview">Thank you for choosing our services!</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">How It Works</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Customers can use gift cards for any service or product</li>
                    <li>Gift cards can be partially used multiple times</li>
                    <li>Remaining balance is tracked automatically</li>
                    <li>Expiration dates help encourage usage</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const amountInput = document.querySelector('input[name="amount"]');
    const expiresInDaysInput = document.querySelector('input[name="expires_in_days"]');
    const messageInput = document.querySelector('textarea[name="message"]');
    
    // Get preview elements
    const amountPreview = document.getElementById('amountPreview');
    const codePreview = document.getElementById('codePreview');
    const expiresPreview = document.getElementById('expiresPreview');
    const messagePreview = document.getElementById('messagePreview');
    
    // Update preview function
    function updatePreview() {
        // Amount
        amountPreview.textContent = (parseFloat(amountInput.value) || 0).toFixed(2);
        
        // Expiration
        if (expiresInDaysInput.value) {
            const expiresDate = new Date();
            expiresDate.setDate(expiresDate.getDate() + parseInt(expiresInDaysInput.value));
            expiresPreview.textContent = expiresDate.toLocaleDateString();
        } else {
            expiresPreview.textContent = 'Never';
        }
        
        // Message
        messagePreview.textContent = messageInput.value || 'Thank you for choosing our services!';
    }
    
    // Add event listeners
    amountInput.addEventListener('input', updatePreview);
    expiresInDaysInput.addEventListener('input', updatePreview);
    messageInput.addEventListener('input', updatePreview);
    
    // Initial update
    updatePreview();
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
    font-size: 2.5rem;
    font-weight: bold;
    margin: 20px 0;
}

.gift-card-code {
    font-family: monospace;
    background: rgba(0,0,0,0.2);
    padding: 5px 10px;
    border-radius: 5px;
    margin: 10px 0;
}

.gift-card-expires {
    font-size: 0.9rem;
    margin: 10px 0;
}

.gift-card-message {
    font-size: 0.85rem;
    font-style: italic;
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px dashed rgba(255,255,255,0.5);
}
</style>
@endsection