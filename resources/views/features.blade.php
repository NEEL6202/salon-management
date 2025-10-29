<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - Salon Management Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="landing-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3">
                <a href="/" class="navbar-brand">
                    <i class="fas fa-cut me-2"></i>
                    Salon Pro
                </a>
                <div class="d-none d-md-flex align-items-center gap-4">
                    <a href="/#features" class="nav-link">Features</a>
                    <a href="/#pricing" class="nav-link">Pricing</a>
                    <a href="{{ route('about') }}" class="nav-link">About</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="min-height: 50vh;">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8 hero-content">
                    <h1 class="hero-title">Powerful Features for Modern Salons</h1>
                    <p class="hero-subtitle">
                        Everything you need to streamline operations and grow your beauty business
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Detail Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- Smart Booking -->
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon primary d-inline-flex mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h2 class="section-title mb-3">Smart Booking System</h2>
                    <p class="lead text-muted mb-4">
                        Let customers book appointments 24/7 with our intuitive online booking system.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Online appointment scheduling</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Automatic SMS & email reminders</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Calendar sync (Google, Outlook)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Recurring appointments</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Waitlist management</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <i class="fas fa-calendar-alt fa-10x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics -->
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="feature-icon success d-inline-flex mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h2 class="section-title mb-3">Analytics & Reports</h2>
                    <p class="lead text-muted mb-4">
                        Make data-driven decisions with comprehensive business analytics.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Real-time revenue tracking</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Staff performance metrics</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Customer retention analysis</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Service popularity trends</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Exportable reports (PDF, Excel)</li>
                    </ul>
                </div>
                <div class="col-lg-6 order-lg-1 text-center">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <i class="fas fa-chart-bar fa-10x text-success opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon warning d-inline-flex mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="fas fa-box"></i>
                    </div>
                    <h2 class="section-title mb-3">Inventory Management</h2>
                    <p class="lead text-muted mb-4">
                        Never run out of products with automated inventory tracking.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Real-time stock levels</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Low stock alerts</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Supplier management</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Barcode scanning</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Purchase order tracking</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <i class="fas fa-boxes fa-10x text-warning opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer CRM -->
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="feature-icon danger d-inline-flex mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h2 class="section-title mb-3">Customer CRM</h2>
                    <p class="lead text-muted mb-4">
                        Build lasting relationships with comprehensive customer profiles.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Detailed customer profiles</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Service history tracking</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Customer preferences & notes</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Loyalty rewards program</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Birthday & anniversary reminders</li>
                    </ul>
                </div>
                <div class="col-lg-6 order-lg-1 text-center">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <i class="fas fa-address-book fa-10x text-danger opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Processing -->
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon info d-inline-flex mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h2 class="section-title mb-3">Payment Processing</h2>
                    <p class="lead text-muted mb-4">
                        Accept payments seamlessly with secure, integrated payment solutions.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Stripe integration</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Multiple payment methods</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Automatic invoicing</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tipping options</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Refund management</li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <i class="fas fa-dollar-sign fa-10x text-info opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Management -->
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="feature-icon secondary d-inline-flex mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h2 class="section-title mb-3">Staff Management</h2>
                    <p class="lead text-muted mb-4">
                        Efficiently manage your team with powerful staff tools.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Staff scheduling</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Commission tracking</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Performance analytics</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Time-off management</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Role-based permissions</li>
                    </ul>
                </div>
                <div class="col-lg-6 order-lg-1 text-center">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <i class="fas fa-users-cog fa-10x text-secondary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="cta-title">See Salon Pro in Action</h2>
            <p class="cta-text">
                Start your free 14-day trial and experience all these features firsthand
            </p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                <i class="fas fa-rocket me-2"></i>
                Start Free Trial
            </a>
        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
