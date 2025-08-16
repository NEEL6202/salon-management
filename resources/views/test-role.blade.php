@extends('layouts.app')

@section('title', 'Test Role')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Role Test</h2>
            
            <p>Current user: {{ auth()->user()->name }}</p>
            <p>User roles: {{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
            
            <h3>Role Checks:</h3>
            
            @role('super_admin')
                <p class="text-success">✅ User has super_admin role</p>
            @else
                <p class="text-danger">❌ User does NOT have super_admin role</p>
            @endrole
            
            @role('salon_owner')
                <p class="text-success">✅ User has salon_owner role</p>
            @else
                <p class="text-danger">❌ User does NOT have salon_owner role</p>
            @endrole
            
            @role('manager')
                <p class="text-success">✅ User has manager role</p>
            @else
                <p class="text-danger">❌ User does NOT have manager role</p>
            @endrole
            
            @role('employee')
                <p class="text-success">✅ User has employee role</p>
            @else
                <p class="text-danger">❌ User does NOT have employee role</p>
            @endrole
            
            @role('customer')
                <p class="text-success">✅ User has customer role</p>
            @else
                <p class="text-danger">❌ User does NOT have customer role</p>
            @endrole
            
            <h3>Multiple Role Check:</h3>
            @role('salon_owner|manager|employee')
                <p class="text-success">✅ User has salon_owner, manager, or employee role</p>
            @else
                <p class="text-danger">❌ User does NOT have salon_owner, manager, or employee role</p>
            @endrole
        </div>
    </div>
</div>
@endsection 