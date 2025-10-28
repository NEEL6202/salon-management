@extends('layouts.modern')

@section('title', $page->title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Page Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.pages.preview', $page) }}" target="_blank" class="btn btn-info">
                    <i class="fas fa-external-link-alt"></i> Preview
                </a>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Pages
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Page Content -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Page Content</h5>
                </div>
                <div class="card-body">
                    @if($page->featured_image)
                        <div class="text-center mb-4">
                            <img src="{{ $page->featured_image }}" alt="{{ $page->title }}" class="img-fluid rounded" style="max-height: 400px;">
                        </div>
                    @endif

                    <h1 class="mb-3">{{ $page->title }}</h1>
                    
                    @if($page->excerpt)
                        <div class="lead mb-4">{{ $page->excerpt }}</div>
                    @endif

                    <div class="page-meta mb-4">
                        <span class="badge badge-{{ $page->status === 'published' ? 'success' : ($page->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($page->status) }}
                        </span>
                        <span class="badge badge-secondary ml-2">{{ ucfirst(str_replace('-', ' ', $page->template)) }}</span>
                        @if($page->is_homepage)
                            <span class="badge badge-primary ml-2">Homepage</span>
                        @endif
                        @if($page->is_footer)
                            <span class="badge badge-info ml-2">Footer</span>
                        @endif
                        <span class="text-muted ml-3">
                            <i class="fas fa-calendar"></i> {{ $page->created_at->format('M d, Y H:i') }}
                        </span>
                        @if($page->published_at)
                            <span class="text-muted ml-3">
                                <i class="fas fa-clock"></i> Published: {{ $page->published_at->format('M d, Y H:i') }}
                            </span>
                        @endif
                        <span class="text-muted ml-3">
                            <i class="fas fa-eye"></i> {{ number_format($page->views) }} views
                        </span>
                        <span class="text-muted ml-3">
                            <i class="fas fa-sort"></i> Order: {{ $page->sort_order ?? 0 }}
                        </span>
                    </div>

                    <div class="page-content">
                        {!! nl2br(e($page->content)) !!}
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
                                <p class="form-control-static">{{ $page->meta_title ?: 'Not set' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><strong>Slug:</strong></label>
                            <p class="form-control-static"><code>{{ $page->slug }}</code></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>Meta Description:</strong></label>
                        <p class="form-control-static">{{ $page->meta_description ?: 'Not set' }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Meta Keywords:</strong></label>
                        <p class="form-control-static">{{ $page->meta_keywords ?: 'Not set' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Page Information -->
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

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Page
                    </a>
                    <a href="{{ route('admin.pages.preview', $page) }}" target="_blank" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-external-link-alt"></i> Preview Page
                    </a>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="fas fa-list"></i> Back to Pages
                    </a>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-outline-success btn-block mb-2">
                        <i class="fas fa-plus"></i> Create New Page
                    </a>
                </div>
            </div>

            <!-- Status Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Status Actions</h5>
                </div>
                <div class="card-body">
                    @if($page->status !== 'published')
                        <button class="btn btn-success btn-block mb-2" onclick="togglePageStatus({{ $page->id }})">
                            <i class="fas fa-check-circle"></i> Publish
                        </button>
                    @else
                        <button class="btn btn-warning btn-block mb-2" onclick="togglePageStatus({{ $page->id }})">
                            <i class="fas fa-pause"></i> Unpublish
                        </button>
                    @endif
                    
                    @if($page->status !== 'archived')
                        <button class="btn btn-secondary btn-block mb-2" onclick="archivePage({{ $page->id }})">
                            <i class="fas fa-archive"></i> Archive
                        </button>
                    @endif

                    @if(!$page->is_homepage)
                        <button class="btn btn-primary btn-block mb-2" onclick="setHomepage({{ $page->id }})">
                            <i class="fas fa-home"></i> Set as Homepage
                        </button>
                    @endif

                    <button class="btn btn-outline-info btn-block mb-2" onclick="duplicatePage({{ $page->id }})">
                        <i class="fas fa-copy"></i> Duplicate Page
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle page status
function togglePageStatus(pageId) {
    if (confirm('Are you sure you want to change the status of this page?')) {
        fetch(`/admin/pages/${pageId}/toggle-status`, {
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
            alert('An error occurred while updating the page status.');
        });
    }
}

// Set page as homepage
function setHomepage(pageId) {
    if (confirm('Are you sure you want to set this page as the homepage?')) {
        fetch(`/admin/pages/${pageId}/set-homepage`, {
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
            alert('An error occurred while setting the homepage.');
        });
    }
}

// Duplicate page
function duplicatePage(pageId) {
    if (confirm('Are you sure you want to duplicate this page?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pages/${pageId}/duplicate`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Archive page
function archivePage(pageId) {
    if (confirm('Are you sure you want to archive this page?')) {
        // You can implement archive functionality here
        alert('Archive functionality will be implemented');
    }
}
</script>
@endpush

