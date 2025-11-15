@extends('layouts.modern')

@section('title', 'Edit Loyalty Program')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Loyalty Program</h1>
        <p class="page-subtitle">Modify your loyalty program settings</p>
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
                <form action="{{ route('salon.loyalty.update', $loyaltyProgram) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Program Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $loyaltyProgram->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $loyaltyProgram->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Points per Dollar Spent</label>
                                <input type="number" name="points_per_dollar" class="form-control" value="{{ old('points_per_dollar', $loyaltyProgram->points_per_dollar) }}" min="1" required>
                                <div class="form-text">How many points customers earn for each dollar spent</div>
                                @error('points_per_dollar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Points Required for Reward</label>
                                <input type="number" name="points_required" class="form-control" value="{{ old('points_required', $loyaltyProgram->points_required) }}" min="1" required>
                                <div class="form-text">How many points needed to redeem a reward</div>
                                @error('points_required')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reward Value ($)</label>
                        <input type="number" name="reward_value" class="form-control" step="0.01" value="{{ old('reward_value', $loyaltyProgram->reward_value) }}" min="0.01" required>
                        <div class="form-text">Monetary value of each reward</div>
                        @error('reward_value')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $loyaltyProgram->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Uncheck to disable this program</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('salon.loyalty.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Program</button>
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
                        <li>Customers earn <strong id="pointsPerDollarPreview">{{ $loyaltyProgram->points_per_dollar }}</strong> point for every $1 spent</li>
                        <li><strong id="pointsRequiredPreview">{{ $loyaltyProgram->points_required }}</strong> points = $<span id="rewardValuePreview">{{ number_format($loyaltyProgram->reward_value, 2) }}</span> reward</li>
                        <li>That's <strong id="valuePerPointPreview">{{ number_format(($loyaltyProgram->reward_value / $loyaltyProgram->points_required * 100), 2) }}</strong>¢ per point value</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6>Example:</h6>
                    <p class="mb-0">A $50 service = <strong id="pointsFor50Preview">{{ 50 * $loyaltyProgram->points_per_dollar }}</strong> points<br>
                    After 2 visits = <strong id="pointsFor100Preview">{{ 100 * $loyaltyProgram->points_per_dollar }}</strong> points = Reward!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection