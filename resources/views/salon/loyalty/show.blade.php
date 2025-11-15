@extends('layouts.modern')

@section('title', 'Loyalty Program Details')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $loyaltyProgram->name }}</h1>
        <p class="page-subtitle">Loyalty program details and customer tracking</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.loyalty.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Programs
        </a>
        <a href="{{ route('salon.loyalty.edit', $loyaltyProgram) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Program
        </a>
    </div>
</div>

<!-- Program Overview -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $loyaltyProgram->loyaltyPoints()->distinct('user_id')->count() }}</h3>
                    <p class="stat-label">Active Members</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-success">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $loyaltyProgram->loyaltyPoints()->sum('points') }}</h3>
                    <p class="stat-label">Total Points Earned</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-info">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $loyaltyProgram->loyaltyRewards()->count() }}</h3>
                    <p class="stat-label">Rewards Redeemed</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">${{ number_format($loyaltyProgram->loyaltyRewards()->sum('value'), 2) }}</h3>
                    <p class="stat-label">Value Redeemed</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Points -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Points Activity</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Points</th>
                                <th>Source</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerPoints as $point)
                            <tr>
                                <td>{{ $point->user->name }}</td>
                                <td><span class="badge bg-primary">{{ $point->points }}</span></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $point->source)) }}</td>
                                <td>{{ $point->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No points activity yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $customerPoints->links() }}
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Rewards</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Points</th>
                                <th>Value</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerRewards as $reward)
                            <tr>
                                <td>{{ $reward->user->name }}</td>
                                <td><span class="badge bg-success">{{ $reward->points_redeemed }}</span></td>
                                <td>${{ number_format($reward->value, 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $reward->type)) }}</td>
                                <td>{{ $reward->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No rewards redeemed yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $customerRewards->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Award Points Form -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Award Points to Customer</h5>
    </div>
    <div class="card-body">
        <form id="awardPointsForm">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach($loyaltyProgram->salon->users()->whereHas('roles', function($q) { $q->where('name', 'customer'); })->get() as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Points</label>
                        <input type="number" name="points" class="form-control" min="1" required>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <select name="source" class="form-select" required>
                            <option value="">Select Source</option>
                            <option value="appointment">Appointment</option>
                            <option value="order">Product Purchase</option>
                            <option value="manual">Manual Entry</option>
                            <option value="referral">Referral</option>
                            <option value="review">Review</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Award Points
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('awardPointsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("salon.loyalty.award-points", $loyaltyProgram) }}', {
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
            alert('Points awarded successfully!');
            this.reset();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while awarding points.');
    });
});
</script>

<style>
.stat-card {
    border: none;
    box-shadow: var(--shadow-sm);
}

.stat-card .card-body {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
}
</style>
@endsection