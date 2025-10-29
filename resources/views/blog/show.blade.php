<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }} - Salon Management Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="landing-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3">
                <a href="/" class="navbar-brand"><i class="fas fa-cut me-2"></i> Salon Pro</a>
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

    <!-- Blog Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <a href="{{ route('blog.index') }}" class="text-decoration-none d-inline-flex align-items-center mb-4">
                        <i class="fas fa-arrow-left me-2"></i> Back to Blog
                    </a>

                    <article>
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" 
                                 class="img-fluid rounded mb-4 w-100" 
                                 alt="{{ $blog->title }}"
                                 style="max-height: 400px; object-fit: cover;">
                        @endif

                        <div class="mb-3">
                            <span class="badge bg-primary me-2">{{ ucfirst($blog->category->name ?? 'General') }}</span>
                            <span class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ $blog->published_at ? $blog->published_at->format('F d, Y') : '' }}
                            </span>
                            @if($blog->author)
                                <span class="text-muted ms-3">
                                    <i class="far fa-user me-1"></i>
                                    By {{ $blog->author->name }}
                                </span>
                            @endif
                        </div>

                        <h1 class="mb-4">{{ $blog->title }}</h1>

                        <div class="blog-content">
                            {!! $blog->content !!}
                        </div>

                        @if($blog->tags)
                            <div class="mt-4">
                                <strong>Tags:</strong>
                                @foreach(explode(',', $blog->tags) as $tag)
                                    <span class="badge bg-secondary me-1">{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        @endif
                    </article>

                    <hr class="my-5">

                    <!-- Share -->
                    <div class="mb-4">
                        <h5>Share this post:</h5>
                        <div class="d-flex gap-2">
                            <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $blog->slug)) }}" 
                               class="btn btn-outline-primary" target="_blank">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}" 
                               class="btn btn-outline-info" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://linkedin.com/shareArticle?url={{ urlencode(route('blog.show', $blog->slug)) }}" 
                               class="btn btn-outline-primary" target="_blank">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 100px;">
                        @if($relatedBlogs->count() > 0)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Related Posts</h5>
                                    @foreach($relatedBlogs as $related)
                                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <a href="{{ route('blog.show', $related->slug) }}" class="text-decoration-none">
                                                <h6 class="mb-1">{{ $related->title }}</h6>
                                            </a>
                                            <small class="text-muted">
                                                {{ $related->published_at ? $related->published_at->format('M d, Y') : '' }}
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="card border-0 shadow-sm" style="background: var(--gradient-primary);">
                            <div class="card-body text-white text-center p-4">
                                <i class="fas fa-rocket fa-3x mb-3 opacity-75"></i>
                                <h5 class="mb-2">Ready to Get Started?</h5>
                                <p class="mb-3">Join thousands of salon owners managing their business with Salon Pro</p>
                                <a href="{{ route('register') }}" class="btn btn-light">Start Free Trial</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
