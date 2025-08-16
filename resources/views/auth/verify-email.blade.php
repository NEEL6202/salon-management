@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Verify Your Email Address</h4>
                </div>
                <div class="card-body p-4 text-center">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif

                    <div class="mb-4">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <h5>Check Your Email</h5>
                        <p class="text-muted">
                            Before proceeding, please check your email for a verification link. 
                            If you did not receive the email, we will gladly send you another.
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                Resend Verification Email
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('logout') }}" class="text-decoration-none" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 