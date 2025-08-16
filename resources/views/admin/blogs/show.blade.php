@extends('layouts.app')

@section('title', $blog->title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Blog Post Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Blogs
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Blog Content -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Blog Post Content</h5>
                </div>
                <div class="card-body">
                    @if($blog->featured_image)
                        <div class="text-center mb-4">
                            <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="img-fluid rounded" style="max-height: 400px;">
                        </div>
                    @endif

                    <h1 class="mb-3">{{ $blog->title }}</h1>
                    
                    @if($blog->excerpt)
                        <div class="lead mb-4">{{ $blog->excerpt }}</div>
                    @endif

                    <div class="blog-meta mb-4">
                        <span class="badge badge-{{ $blog->status === 'published' ? 'success' : ($blog->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $blog->status)) }}
                        </span>
                        @if($blog->category)
                            <span class="badge badge-info ml-2">{{ $blog->category->name }}</span>
                        @endif
                        <span class="text-muted ml-3">
                            <i class="fas fa-user"></i> {{ $blog->author->name ?? 'Unknown' }}
                        </span>
                        <span class="text-muted ml-3">
                            <i class="fas fa-calendar"></i> {{ $blog->created_at->format('M d, Y H:i') }}
                        </span>
                        @if($blog->published_at)
                            <span class="text-muted ml-3">
                                <i class="fas fa-clock"></i> Published: {{ $blog->published_at->format('M d, Y H:i') }}
                            </span>
                        @endif
                        <span class="text-muted ml-3">
                            <i class="fas fa-eye"></i> {{ number_format($blog->views) }} views
                        </span>
                    </div>

                    @if($blog->tags)
                        <div class="mb-4">
                            <strong>Tags:</strong>
                            @foreach(explode(',', $blog->tags) as $tag)
                                <span class="badge badge-light mr-1">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="blog-content">
                        {!! nl2br(e($blog->content)) !!}
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">SEO Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Meta Title:</strong></label>
                                <p class="form-control-static">{{ $blog->meta_title ?: 'Not set' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><strong>Slug:</strong></label>
                            <p class="form-control-static"><code>{{ $blog->slug }}</code></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>Meta Description:</strong></label>
                        <p class="form-control-static">{{ $blog->meta_description ?: 'Not set' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Blog Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Blog Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>ID:</strong> #{{ $blog->id }}
                        </li>
                        <li class="mb-2">
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $blog->status === 'published' ? 'success' : ($blog->status === 'draft' ? 'warning' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $blog->status)) }}
                            </span>
                        </li>
                        <li class="mb-2">
                            <strong>Category:</strong> {{ $blog->category->name ?? 'No Category' }}
                        </li>
                        <li class="mb-2">
                            <strong>Author:</strong> {{ $blog->author->name ?? 'Unknown' }}
                        </li>
                        <li class="mb-2">
                            <strong>Created:</strong> {{ $blog->created_at->format('M d, Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Updated:</strong> {{ $blog->updated_at->format('M d, Y H:i') }}
                        </li>
                        @if($blog->published_at)
                            <li class="mb-2">
                                <strong>Published:</strong> {{ $blog->published_at->format('M d, Y H:i') }}
                            </li>
                        @endif
                        <li class="mb-2">
                            <strong>Views:</strong> {{ number_format($blog->views) }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Blog Post
                    </a>
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="fas fa-list"></i> Back to Blogs
                    </a>
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-outline-success btn-block mb-2">
                        <i class="fas fa-plus"></i> Create New Blog
                    </a>
                </div>
            </div>

            <!-- Status Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Status Actions</h5>
                </div>
                <div class="card-body">
                    @if($blog->status !== 'published')
                        <button class="btn btn-success btn-block mb-2" onclick="toggleBlogStatus({{ $blog->id }})">
                            <i class="fas fa-check-circle"></i> Publish
                        </button>
                    @else
                        <button class="btn btn-warning btn-block mb-2" onclick="toggleBlogStatus({{ $blog->id }})">
                            <i class="fas fa-pause"></i> Unpublish
                        </button>
                    @endif
                    
                    @if($blog->status !== 'archived')
                        <button class="btn btn-secondary btn-block mb-2" onclick="archiveBlog({{ $blog->id }})">
                            <i class="fas fa-archive"></i> Archive
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle blog status
function toggleBlogStatus(blogId) {
    if (confirm('Are you sure you want to change the status of this blog post?')) {
        fetch(`/admin/blogs/${blogId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the blog status.');
        });
    }
}

// Archive blog
function archiveBlog(blogId) {
    if (confirm('Are you sure you want to archive this blog post?')) {
        // You can implement archive functionality here
        alert('Archive functionality will be implemented');
    }
}
</script>
@endpush

