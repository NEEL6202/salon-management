@extends('layouts.app')

@section('title', 'Edit Page')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Edit Page</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Page Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $page->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="Leave empty to auto-generate from title">
                            <small class="form-text text-muted">URL-friendly version of the title (e.g., "about-us" for "About Us")</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Excerpt</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" name="excerpt" rows="3" placeholder="Brief description of the page...">{{ old('excerpt', $page->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="15" required>{{ old('content', $page->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Template <span class="text-danger">*</span></label>
                                    <select class="form-control @error('template') is-invalid @enderror" name="template" required>
                                        @foreach($templates as $template)
                                            <option value="{{ $template }}" {{ old('template', $page->template) == $template ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('-', ' ', $template)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('template')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $page->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
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
                            @if($page->featured_image)
                                <div class="mb-2">
                                    <img src="{{ $page->featured_image }}" alt="Current featured image" class="img-thumbnail" style="max-width: 200px;">
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" min="0">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Published At</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" name="published_at" value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_homepage" name="is_homepage" value="1" {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_homepage">Set as Homepage</label>
                                <small class="form-text text-muted">Only one page can be the homepage</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_footer" name="is_footer" value="1" {{ old('is_footer', $page->is_footer) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_footer">Show in Footer</label>
                                <small class="form-text text-muted">Display this page in the footer navigation</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" maxlength="60">
                            <small class="form-text text-muted">Max 60 characters for SEO</small>
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" rows="3" maxlength="160">{{ old('meta_description', $page->meta_description) }}</textarea>
                            <small class="form-text text-muted">Max 160 characters for SEO</small>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Meta Keywords</label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" placeholder="Enter keywords separated by commas">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Page
                            </button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
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
                    <h5 class="card-title">Page Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>ID:</strong> #{{ $page->id }}
                        </li>
                        <li class="mb-2">
                            <strong>Template:</strong> {{ ucfirst(str_replace('-', ' ', $page->template)) }}
                        </li>
                        <li class="mb-2">
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $page->status === 'published' ? 'success' : ($page->status === 'draft' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($page->status) }}
                            </span>
                        </li>
                        <li class="mb-2">
                            <strong>Created:</strong> {{ $page->created_at->format('M d, Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Updated:</strong> {{ $page->updated_at->format('M d, Y H:i') }}
                        </li>
                        @if($page->published_at)
                            <li class="mb-2">
                                <strong>Published:</strong> {{ $page->published_at->format('M d, Y H:i') }}
                            </li>
                        @endif
                        <li class="mb-2">
                            <strong>Views:</strong> {{ number_format($page->views) }}
                        </li>
                        <li class="mb-2">
                            <strong>Sort Order:</strong> {{ $page->sort_order ?? 0 }}
                        </li>
                        <li class="mb-2">
                            <strong>Homepage:</strong> 
                            @if($page->is_homepage)
                                <span class="badge badge-primary">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong>Footer:</strong> 
                            @if($page->is_footer)
                                <span class="badge badge-info">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.pages.show', $page) }}" class="btn btn-outline-info btn-block mb-2">
                        <i class="fas fa-eye"></i> View Page
                    </a>
                    <a href="{{ route('admin.pages.preview', $page) }}" target="_blank" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="fas fa-external-link-alt"></i> Preview
                    </a>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="fas fa-list"></i> Back to Pages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-generate slug from title if empty
document.querySelector('input[name="title"]').addEventListener('input', function() {
    const slug = document.querySelector('input[name="slug"]');
    if (!slug.value) {
        slug.value = this.value.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }
});

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

