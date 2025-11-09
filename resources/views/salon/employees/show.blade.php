@extends('layouts.modern')

@section('title', 'Employee Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Employee Details</h5>
        </div>
        <div class="card-body">
            <h4>{{ $employee->name }}</h4>
            <p>Email: {{ $employee->email }}</p>
            <p>Role: {{ $employee->roles->first()->name ?? 'No role' }}</p>
            <p>Status: {{ $employee->status }}</p>
            <a href="{{ route('salon.employees.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection 
