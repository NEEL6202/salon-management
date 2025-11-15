@extends('layouts.modern')

@section('title', 'Loyalty Programs')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Loyalty Programs</h1>
        <p class="page-subtitle">Manage your salon's loyalty programs</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.loyalty.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Program
        </a>
    </div>
</div>

<!-- Loyalty Programs Table -->
<div class="card">
    <div class="card-body">
        @if($loyaltyPrograms->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Points per $</th>
                        <th>Points for Reward</th>
                        <th>Reward Value</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loyaltyPrograms as $program)
                    <tr>
                        <td>
                            <strong>{{ $program->name }}</strong>
                        </td>
                        <td>{{ Str::limit($program->description, 50) }}</td>
                        <td>{{ $program->points_per_dollar }}</td>
                        <td>{{ $program->points_required }}</td>
                        <td>${{ number_format($program->reward_value, 2) }}</td>
                        <td>
                            @if($program->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('salon.loyalty.show', $program) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('salon.loyalty.edit', $program) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('salon.loyalty.destroy', $program) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this program?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $loyaltyPrograms->links() }}
        @else
        <div class="text-center py-5">
            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
            <h5>No Loyalty Programs Found</h5>
            <p class="text-muted">Create your first loyalty program to start rewarding customers.</p>
            <a href="{{ route('salon.loyalty.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Program
            </a>
        </div>
        @endif
    </div>
</div>
@endsection