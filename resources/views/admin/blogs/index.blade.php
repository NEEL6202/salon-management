@extends('layouts.modern')

@section('title', 'Manage Blogs')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Blog Management</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Blogs</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Blog Post
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fas fa-newspaper"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h6>Total Blogs</h6>
                            <h3>{{ $stats['total_blogs'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h6>Published</h6>
                            <h3>{{ $stats['published_blogs'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-warning">
                            <i class="fas fa-edit"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h6>Drafts</h6>
                            <h3>{{ $stats['draft_blogs'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-info">
                            <i class="fas fa-eye"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h6>Total Views</h6>
                            <h3>{{ number_format($stats['total_views']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.blogs.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search blogs...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Author</label>
                            <select class="form-control" name="author">
                                <option value="">All Authors</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.blogs.export', ['format' => 'csv']) }}">CSV</a>
                                <a class="dropdown-item" href="{{ route('admin.blogs.export', ['format' => 'json']) }}">JSON</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card" id="bulkActionsCard" style="display: none;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.blogs.bulk-action') }}" id="bulkActionForm">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Bulk Action</label>
                            <select class="form-control" name="action" id="bulkAction">
                                <option value="">Select Action</option>
                                <option value="publish">Publish Selected</option>
                                <option value="unpublish">Unpublish Selected</option>
                                <option value="archive">Archive Selected</option>
                                <option value="delete">Delete Selected</option>
                                <option value="change_category">Change Category</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="categoryChangeDiv" style="display: none;">
                        <div class="form-group">
                            <label>New Category</label>
                            <select class="form-control" name="new_category_id">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary" id="bulkActionBtn" disabled>
                            Apply Action
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="clearBulkSelection()">
                            Clear Selection
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Blogs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $blog)
                        <tr>
                            <td>
                                <input type="checkbox" name="blog_ids[]" value="{{ $blog->id }}" class="blog-checkbox" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($blog->featured_image)
                                        <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $blog->title }}</h6>
                                        @if($blog->excerpt)
                                            <small class="text-muted">{{ Str::limit($blog->excerpt, 60) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($blog->category)
                                    <span class="badge badge-info">{{ $blog->category->name }}</span>
                                @else
                                    <span class="text-muted">No Category</span>
                                @endif
                            </td>
                            <td>{{ $blog->author->name ?? 'Unknown' }}</td>
                            <td>
                                @switch($blog->status)
                                    @case('published')
                                        <span class="badge badge-success">Published</span>
                                        @break
                                    @case('draft')
                                        <span class="badge badge-warning">Draft</span>
                                        @break
                                    @case('archived')
                                        <span class="badge badge-secondary">Archived</span>
                                        @break
                                    @case('pending_review')
                                        <span class="badge badge-info">Pending Review</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ ucfirst($blog->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ number_format($blog->views) }}</td>
                            <td>{{ $blog->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.blogs.show', $blog) }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.blogs.edit', $blog) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a class="dropdown-item" href="#" onclick="toggleBlogStatus({{ $blog->id }})">
                                            <i class="fas fa-toggle-on"></i> Toggle Status
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#" onclick="deleteBlog({{ $blog->id }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-newspaper fa-3x mb-3"></i>
                                    <h5>No blogs found</h5>
                                    <p>Create your first blog post to get started.</p>
                                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Blog Post
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($blogs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this blog post? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="POST" action="" id="deleteForm" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Bulk actions functionality
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.blog-checkbox:checked');
    const bulkActionsCard = document.getElementById('bulkActionsCard');
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    
    if (checkboxes.length > 0) {
        bulkActionsCard.style.display = 'block';
        bulkActionBtn.disabled = false;
    } else {
        bulkActionsCard.style.display = 'none';
        bulkActionBtn.disabled = true;
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.blog-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function clearBulkSelection() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.blog-checkbox');
    
    selectAll.checked = false;
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    updateBulkActions();
}

// Show/hide category change div based on bulk action
document.getElementById('bulkAction').addEventListener('change', function() {
    const categoryChangeDiv = document.getElementById('categoryChangeDiv');
    if (this.value === 'change_category') {
        categoryChangeDiv.style.display = 'block';
    } else {
        categoryChangeDiv.style.display = 'none';
    }
});

// Toggle blog status
function toggleBlogStatus(blogId) {
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

// Delete blog confirmation
function deleteBlog(blogId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/blogs/${blogId}`;
    $('#deleteModal').modal('show');
}

// Auto-submit form on filter change
document.querySelectorAll('#filterForm select, #filterForm input[type="date"]').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endpush

