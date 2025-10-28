@extends('layouts.modern')

@section('title', 'Edit Blog Post')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Edit Blog Post</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Blog Post</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $blog->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Excerpt</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" name="excerpt" rows="3" placeholder="Brief description of the blog post...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="10" required>{{ old('content', $blog->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $blog->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Featured Image</label>
                            @if($blog->featured_image)
                                <div class="mb-2">
                                    <img src="{{ $blog->featured_image }}" alt="Current featured image" class="img-thumbnail" style="max-width: 200px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                        <label class="form-check-label" for="remove_image">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control-file @error('featured_image') is-invalid @enderror" name="featured_image" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 1200x630 pixels. Max file size: 2MB.</small>
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" name="tags" value="{{ old('tags', $blog->tags) }}" placeholder="Enter tags separated by commas">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" maxlength="60">
                                    <small class="form-text text-muted">Max 60 characters for SEO</small>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Published At</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" name="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" rows="3" maxlength="160">{{ old('meta_description', $blog->meta_description) }}</textarea>
                            <small class="form-text text-muted">Max 160 characters for SEO</small>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Blog Post
                            </button>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Blog Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Author:</strong> {{ $blog->author->name ?? 'Unknown' }}
                        </li>
                        <li class="mb-2">
                            <strong>Created:</strong> {{ $blog->created_at->format('M d, Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Last Updated:</strong> {{ $blog->updated_at->format('M d, Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Views:</strong> {{ number_format($blog->views) }}
                        </li>
                        <li class="mb-2">
                            <strong>Slug:</strong> <code>{{ $blog->slug }}</code>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.blogs.show', $blog) }}" class="btn btn-outline-info btn-block mb-2">
                        <i class="fas fa-eye"></i> View Blog Post
                    </a>
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="fas fa-list"></i> Back to Blogs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-generate meta title from title if empty
document.querySelector('input[name="title"]').addEventListener('input', function() {
    const metaTitle = document.querySelector('input[name="meta_title"]');
    if (!metaTitle.value) {
        metaTitle.value = this.value;
    }
});

// Auto-generate meta description from excerpt if empty
document.querySelector('textarea[name="excerpt"]').addEventListener('input', function() {
    const metaDescription = document.querySelector('textarea[name="meta_description"]');
    if (!metaDescription.value) {
        metaDescription.value = this.value;
    }
});

// Character counter for meta fields
document.querySelector('input[name="meta_title"]').addEventListener('input', function() {
    const maxLength = 60;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    if (remaining < 0) {
        this.value = this.value.substring(0, maxLength);
    }
});

document.querySelector('textarea[name="meta_description"]').addEventListener('input', function() {
    const maxLength = 160;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    if (remaining < 0) {
        this.value = this.value.substring(0, maxLength);
    }
});
</script>
@endpush

