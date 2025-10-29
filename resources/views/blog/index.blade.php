<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Salon Management Pro</title>
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
                    <a href="{{ route('blog.index') }}" class="nav-link">Blog</a>
                    <a href="{{ route('about') }}" class="nav-link">About</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="min-height: 40vh;">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8 hero-content">
                    <h1 class="hero-title">Our Blog</h1>
                    <p class="hero-subtitle">
                        Tips, insights, and industry news for salon professionals
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Listing -->
    <section class="py-5">
        <div class="container">
            @if($blogs->count() > 0)
                <div class="row g-4">
                    @foreach($blogs as $blog)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm">
                                @if($blog->featured_image)
                                    <img src="{{ asset('storage/' . $blog->featured_image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $blog->title }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary">{{ ucfirst($blog->category->name ?? 'General') }}</span>
                                        <small class="text-muted">
                                            <i class="far fa-calendar me-1"></i>
                                            {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Draft' }}
                                        </small>
                                    </div>
                                    <h5 class="card-title">{{ $blog->title }}</h5>
                                    <p class="card-text text-muted">
                                        {{ Str::limit(strip_tags($blog->content), 120) }}
                                    </p>
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-outline-primary">
                                        Read More <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $blogs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                    <h4>No blog posts yet</h4>
                    <p class="text-muted">Check back soon for updates!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
