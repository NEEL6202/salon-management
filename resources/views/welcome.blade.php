@extends('layouts.app')

@section('title', 'Welcome - Salon Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Transform Your Salon Business
                </h1>
                <p class="lead mb-4">
                    Streamline your salon operations with our comprehensive management system. 
                    Handle appointments, inventory, sales, and customer relationships all in one place.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-rocket me-2"></i>
                        Get Started Free
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-play me-2"></i>
                        Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-cut fa-10x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Why Choose Our Platform?</h2>
                <p class="lead text-muted">Everything you need to run a successful salon business</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-calendar-check fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Appointment Management</h5>
                        <p class="card-text text-muted">
                            Easy booking system with calendar integration, reminders, and scheduling tools.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-box fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Inventory Control</h5>
                        <p class="card-text text-muted">
                            Track products, manage stock levels, and get alerts for low inventory.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-chart-line fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title">Analytics & Reports</h5>
                        <p class="card-text text-muted">
                            Detailed insights into your business performance and customer trends.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-users fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Customer Management</h5>
                        <p class="card-text text-muted">
                            Build lasting relationships with customer profiles and loyalty programs.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-credit-card fa-3x text-danger"></i>
                        </div>
                        <h5 class="card-title">Payment Processing</h5>
                        <p class="card-text text-muted">
                            Secure payment solutions with multiple payment methods and invoicing.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-mobile-alt fa-3x text-secondary"></i>
                        </div>
                        <h5 class="card-title">Mobile Friendly</h5>
                        <p class="card-text text-muted">
                            Access your salon management system from anywhere, anytime.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Simple Pricing Plans</h2>
                <p class="lead text-muted">Choose the plan that fits your business needs</p>
            </div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-primary">Starter</h5>
                        <div class="price mb-3">
                            <span class="display-4 fw-bold">$29</span>
                            <span class="text-muted">/month</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Up to 5 employees</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Basic appointment booking</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Inventory management</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Customer database</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Basic reports</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Get Started</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg border-primary">
                    <div class="card-body text-center p-4">
                        <div class="badge bg-primary mb-2">Most Popular</div>
                        <h5 class="card-title text-primary">Professional</h5>
                        <div class="price mb-3">
                            <span class="display-4 fw-bold">$59</span>
                            <span class="text-muted">/month</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Up to 15 employees</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Advanced booking system</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Full inventory control</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Customer loyalty program</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Advanced analytics</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Payment processing</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-primary w-100">Get Started</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-primary">Enterprise</h5>
                        <div class="price mb-3">
                            <span class="display-4 fw-bold">$99</span>
                            <span class="text-muted">/month</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Unlimited employees</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Multi-location support</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Advanced integrations</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority support</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Custom features</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>API access</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Salon?</h2>
                <p class="lead text-muted mb-4">
                    Join thousands of salon owners who have already streamlined their business operations.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i>
                        Start Free Trial
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">
                    <i class="fas fa-cut me-2"></i>
                    Salon Management System
                </h5>
                <p class="text-muted">
                    Empowering salon owners with modern business management tools.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">
                    &copy; {{ date('Y') }} Salon Management System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
}

.min-vh-75 {
    min-height: 75vh;
}

.hero-image {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.feature-icon {
    transition: transform 0.3s ease;
}

.card:hover .feature-icon {
    transform: scale(1.1);
}

.price {
    color: var(--primary-color);
}

.border-primary {
    border-color: var(--primary-color) !important;
}
</style>
@endpush
@endsection
