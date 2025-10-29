<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon Management Pro - Transform Your Salon Business</title>
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
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#pricing" class="nav-link">Pricing</a>
                    <a href="{{ route('blog.index') }}" class="nav-link">Blog</a>
                    <a href="{{ route('about') }}" class="nav-link">About</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                </div>
                <button class="btn d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="collapse d-md-none" id="mobileMenu">
                <div class="pb-3">
                    <a href="#features" class="nav-link d-block py-2">Features</a>
                    <a href="#pricing" class="nav-link d-block py-2">Pricing</a>
                    <a href="{{ route('about') }}" class="nav-link d-block py-2">About</a>
                    <a href="{{ route('contact') }}" class="nav-link d-block py-2">Contact</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100">Get Started</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content fade-in">
                    <h1 class="hero-title">Transform Your Salon Business</h1>
                    <p class="hero-subtitle">
                        All-in-one salon management platform with smart booking, inventory control, 
                        and powerful analytics to grow your business.
                    </p>
                    <div class="hero-buttons d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-rocket me-2"></i>
                            Start Free Trial
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-play-circle me-2"></i>
                            See How It Works
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <div class="hero-image">
                        <i class="fas fa-scissors fa-10x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-item fade-in">
                        <span class="stat-number">10K+</span>
                        <span class="stat-label">Active Salons</span>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-item fade-in">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Appointments/Month</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item fade-in">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Customer Satisfaction</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item fade-in">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support Available</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Powerful Features for Modern Salons</h2>
                <p class="section-subtitle">Everything you need to manage and grow your salon business</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon primary">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Smart Booking System</h3>
                        <p class="feature-text">
                            Online booking with automatic confirmations, reminders, and calendar sync. 
                            Let customers book 24/7.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon success">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Analytics & Reports</h3>
                        <p class="feature-text">
                            Real-time dashboards, revenue tracking, and detailed reports to make 
                            data-driven decisions.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon warning">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3 class="feature-title">Inventory Management</h3>
                        <p class="feature-text">
                            Track products, manage stock levels, get low-stock alerts, and 
                            automate reordering.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon danger">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Customer CRM</h3>
                        <p class="feature-text">
                            Complete customer profiles, visit history, preferences, and 
                            loyalty rewards program.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon info">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="feature-title">Payment Processing</h3>
                        <p class="feature-text">
                            Accept payments online and in-store with Stripe integration. 
                            Automatic invoicing included.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card fade-in">
                        <div class="feature-icon secondary">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Mobile Ready</h3>
                        <p class="feature-text">
                            Access from any device - desktop, tablet, or smartphone. 
                            Responsive design that works everywhere.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Simple, Transparent Pricing</h2>
                <p class="section-subtitle">Choose the perfect plan for your salon</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <h3 class="pricing-name">Starter</h3>
                        <div class="mb-4">
                            <span class="pricing-price">$29</span>
                            <span class="pricing-period">/month</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> Up to 5 staff members</li>
                            <li><i class="fas fa-check"></i> 100 appointments/month</li>
                            <li><i class="fas fa-check"></i> Basic analytics</li>
                            <li><i class="fas fa-check"></i> Email support</li>
                            <li><i class="fas fa-check"></i> Mobile app access</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary pricing-button">
                            Get Started
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card featured">
                        <span class="pricing-badge">Most Popular</span>
                        <h3 class="pricing-name">Professional</h3>
                        <div class="mb-4">
                            <span class="pricing-price">$59</span>
                            <span class="pricing-period">/month</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> Up to 15 staff members</li>
                            <li><i class="fas fa-check"></i> Unlimited appointments</li>
                            <li><i class="fas fa-check"></i> Advanced analytics</li>
                            <li><i class="fas fa-check"></i> Priority support</li>
                            <li><i class="fas fa-check"></i> Loyalty program</li>
                            <li><i class="fas fa-check"></i> Payment processing</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-primary pricing-button">
                            Get Started
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <h3 class="pricing-name">Enterprise</h3>
                        <div class="mb-4">
                            <span class="pricing-price">$99</span>
                            <span class="pricing-period">/month</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> Unlimited staff</li>
                            <li><i class="fas fa-check"></i> Multi-location support</li>
                            <li><i class="fas fa-check"></i> Custom integrations</li>
                            <li><i class="fas fa-check"></i> Dedicated support</li>
                            <li><i class="fas fa-check"></i> API access</li>
                            <li><i class="fas fa-check"></i> White-label options</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary pricing-button">
                            Contact Sales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="cta-title">Ready to Transform Your Salon?</h2>
            <p class="cta-text">
                Join thousands of salon owners who have streamlined their operations with Salon Pro
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-rocket me-2"></i>
                    Start Your Free Trial
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>
                    Contact Sales
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">
                        <i class="fas fa-cut me-2"></i>
                        Salon Pro
                    </h5>
                    <p>
                        The complete salon management solution. Streamline operations, 
                        delight customers, and grow your business.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Product</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">API Reference</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Company</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Get Started</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('register') }}">Sign Up</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('contact') }}">Request Demo</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom text-center">
                <p class="mb-0">
                    &copy; {{ date('Y') }} Salon Management Pro. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.landing-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
