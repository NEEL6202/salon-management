<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Salon Management Pro</title>
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
                    <h1 class="hero-title">About Salon Pro</h1>
                    <p class="hero-subtitle">
                        We're on a mission to empower salon owners with the tools they need to succeed
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">Our Story</h2>
                    <p class="lead text-muted mb-4">
                        Founded in 2020, Salon Pro was born from a simple observation: salon owners 
                        were juggling too many tools and spending more time on admin than on their craft.
                    </p>
                    <p class="text-muted">
                        We set out to create an all-in-one platform that would streamline operations, 
                        delight customers, and help salons grow. Today, we're proud to serve over 10,000 
                        salons worldwide, helping them manage millions of appointments every month.
                    </p>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="feature-icon primary d-inline-flex" style="width: 150px; height: 150px; font-size: 4rem;">
                        <i class="fas fa-heart m-auto"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon success">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="feature-title">Our Mission</h3>
                        <p class="feature-text">
                            To empower salon owners with intuitive technology that simplifies operations, 
                            enhances customer experiences, and drives business growth.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon warning">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="feature-title">Our Vision</h3>
                        <p class="feature-text">
                            To become the world's leading salon management platform, helping beauty 
                            professionals thrive in an increasingly digital world.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Values</h2>
                <p class="section-subtitle">The principles that guide everything we do</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon primary d-inline-flex mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Customer First</h4>
                        <p class="text-muted">
                            Our customers' success is our success. We listen, adapt, and continuously improve.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon success d-inline-flex mb-3">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4>Innovation</h4>
                        <p class="text-muted">
                            We embrace new technologies and ideas to stay ahead of industry trends.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon warning d-inline-flex mb-3">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Integrity</h4>
                        <p class="text-muted">
                            We operate with transparency, honesty, and respect in all our relationships.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="cta-title">Ready to Join Our Community?</h2>
            <p class="cta-text">
                Start your free trial today and see why thousands of salons trust Salon Pro
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
