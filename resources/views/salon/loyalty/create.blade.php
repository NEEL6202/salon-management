@extends('layouts.modern')

@section('title', 'Create Loyalty Program')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Create Loyalty Program</h1>
        <p class="page-subtitle">Set up a new loyalty program for your customers</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.loyalty.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Programs
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Program Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('salon.loyalty.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Program Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Points per Dollar Spent</label>
                                <input type="number" name="points_per_dollar" class="form-control" value="{{ old('points_per_dollar', 1) }}" min="1" required>
                                <div class="form-text">How many points customers earn for each dollar spent</div>
                                @error('points_per_dollar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Points Required for Reward</label>
                                <input type="number" name="points_required" class="form-control" value="{{ old('points_required', 100) }}" min="1" required>
                                <div class="form-text">How many points needed to redeem a reward</div>
                                @error('points_required')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reward Value ($)</label>
                        <input type="number" name="reward_value" class="form-control" step="0.01" value="{{ old('reward_value', 5.00) }}" min="0.01" required>
                        <div class="form-text">Monetary value of each reward</div>
                        @error('reward_value')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Uncheck to disable this program</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('salon.loyalty.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Program Preview</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6>How it works:</h6>
                    <ul class="mb-0">
                        <li>Customers earn <strong id="pointsPerDollarPreview">1</strong> point for every $1 spent</li>
                        <li><strong id="pointsRequiredPreview">100</strong> points = $<span id="rewardValuePreview">5.00</span> reward</li>
                        <li>That's <strong id="valuePerPointPreview">5</strong>¢ per point value</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6>Example:</h6>
                    <p class="mb-0">A $50 service = <strong id="pointsFor50Preview">50</strong> points<br>
                    After 2 visits = <strong id="pointsFor100Preview">100</strong> points = Reward!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const pointsPerDollar = document.querySelector('input[name="points_per_dollar"]');
    const pointsRequired = document.querySelector('input[name="points_required"]');
    const rewardValue = document.querySelector('input[name="reward_value"]');
    
    // Get preview elements
    const pointsPerDollarPreview = document.getElementById('pointsPerDollarPreview');
    const pointsRequiredPreview = document.getElementById('pointsRequiredPreview');
    const rewardValuePreview = document.getElementById('rewardValuePreview');
    const valuePerPointPreview = document.getElementById('valuePerPointPreview');
    const pointsFor50Preview = document.getElementById('pointsFor50Preview');
    const pointsFor100Preview = document.getElementById('pointsFor100Preview');
    
    // Update preview function
    function updatePreview() {
        const ppd = parseInt(pointsPerDollar.value) || 1;
        const pr = parseInt(pointsRequired.value) || 100;
        const rv = parseFloat(rewardValue.value) || 5.00;
        
        pointsPerDollarPreview.textContent = ppd;
        pointsRequiredPreview.textContent = pr;
        rewardValuePreview.textContent = rv.toFixed(2);
        
        const valuePerPoint = (rv / pr * 100).toFixed(2);
        valuePerPointPreview.textContent = valuePerPoint;
        
        const pointsFor50 = 50 * ppd;
        pointsFor50Preview.textContent = pointsFor50;
        
        const pointsFor100 = 100 * ppd;
        pointsFor100Preview.textContent = pointsFor100;
    }
    
    // Add event listeners
    pointsPerDollar.addEventListener('input', updatePreview);
    pointsRequired.addEventListener('input', updatePreview);
    rewardValue.addEventListener('input', updatePreview);
    
    // Initial update
    updatePreview();
});
</script>
@endsection