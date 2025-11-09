@extends('layouts.modern')

@section('title', 'My Customers')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">My Customers</h1>
        <p class="page-subtitle">Customers you've serviced</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($customers->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Appointments</th>
                        <th>Last Visit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($customer->avatar)
                                <img src="{{ Storage::url($customer->avatar) }}" 
                                     alt="{{ $customer->name }}" 
                                     class="rounded-circle me-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $customer->name }}</div>
                                    <small class="text-muted">{{ $customer->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $customer->phone ?: 'N/A' }}</div>
                            <small class="text-muted">{{ $customer->gender ? ucfirst($customer->gender) : 'Not specified' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $customer->appointments_count }} appointments</span>
                        </td>
                        <td>
                            @if($customer->appointments->isNotEmpty())
                                {{ $customer->appointments->sortByDesc('appointment_date')->first()->appointment_date->format('M d, Y') }}
                            @else
                                <span class="text-muted">No visits yet</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('employee.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($customers->hasPages())
        <div class="card-footer">
            {{ $customers->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h5>No Customers Found</h5>
            <p class="text-muted">You haven't serviced any customers yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection
