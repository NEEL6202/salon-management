@extends('layouts.modern')

@section('title', 'Manage Pages')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Page Management (CMS)</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pages</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Page
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
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h6>Total Pages</h6>
                            <h3>{{ $stats['total_pages'] }}</h3>
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
                            <h3>{{ $stats['published_pages'] }}</h3>
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
                            <h3>{{ $stats['draft_pages'] }}</h3>
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
            <form method="GET" action="{{ route('admin.pages.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search pages...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Template</label>
                            <select class="form-control" name="template">
                                <option value="">All Templates</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template }}" {{ request('template') == $template ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('-', ' ', $template)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.pages.export', ['format' => 'csv']) }}">CSV</a>
                                <a class="dropdown-item" href="{{ route('admin.pages.export', ['format' => 'json']) }}">JSON</a>
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
            <form method="POST" action="{{ route('admin.pages.bulk-action') }}" id="bulkActionForm">
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
                                <option value="change_template">Change Template</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="templateChangeDiv" style="display: none;">
                        <div class="form-group">
                            <label>New Template</label>
                            <select class="form-control" name="new_template">
                                @foreach($templates as $template)
                                    <option value="{{ $template }}">{{ ucfirst(str_replace('-', ' ', $template)) }}</option>
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

    <!-- Pages Table -->
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
                            <th>Slug</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                        <tr>
                            <td>
                                <input type="checkbox" name="page_ids[]" value="{{ $page->id }}" class="page-checkbox" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($page->featured_image)
                                        <img src="{{ $page->featured_image }}" alt="{{ $page->title }}" class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">
                                            {{ $page->title }}
                                            @if($page->is_homepage)
                                                <span class="badge badge-primary ml-2">Homepage</span>
                                            @endif
                                            @if($page->is_footer)
                                                <span class="badge badge-info ml-2">Footer</span>
                                            @endif
                                        </h6>
                                        @if($page->excerpt)
                                            <small class="text-muted">{{ Str::limit($page->excerpt, 60) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $page->slug }}</code>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ ucfirst(str_replace('-', ' ', $page->template)) }}</span>
                            </td>
                            <td>
                                @switch($page->status)
                                    @case('published')
                                        <span class="badge badge-success">Published</span>
                                        @break
                                    @case('draft')
                                        <span class="badge badge-warning">Draft</span>
                                        @break
                                    @case('archived')
                                        <span class="badge badge-secondary">Archived</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ ucfirst($page->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ number_format($page->views) }}</td>
                            <td>{{ $page->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.pages.show', $page) }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.pages.preview', $page) }}" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> Preview
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.pages.edit', $page) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a class="dropdown-item" href="#" onclick="togglePageStatus({{ $page->id }})">
                                            <i class="fas fa-toggle-on"></i> Toggle Status
                                        </a>
                                        @if(!$page->is_homepage)
                                            <a class="dropdown-item" href="#" onclick="setHomepage({{ $page->id }})">
                                                <i class="fas fa-home"></i> Set as Homepage
                                            </a>
                                        @endif
                                        <a class="dropdown-item" href="#" onclick="duplicatePage({{ $page->id }})">
                                            <i class="fas fa-copy"></i> Duplicate
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        @if(!$page->is_homepage)
                                            <a class="dropdown-item text-danger" href="#" onclick="deletePage({{ $page->id }})">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        @else
                                            <span class="dropdown-item text-muted">
                                                <i class="fas fa-lock"></i> Cannot delete homepage
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <h5>No pages found</h5>
                                    <p>Create your first page to get started.</p>
                                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Page
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pages->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $pages->links() }}
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
                <p>Are you sure you want to delete this page? This action cannot be undone.</p>
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
    const checkboxes = document.querySelectorAll('.page-checkbox:checked');
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
    const checkboxes = document.querySelectorAll('.page-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function clearBulkSelection() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.page-checkbox');
    
    selectAll.checked = false;
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    updateBulkActions();
}

// Show/hide template change div based on bulk action
document.getElementById('bulkAction').addEventListener('change', function() {
    const templateChangeDiv = document.getElementById('templateChangeDiv');
    if (this.value === 'change_template') {
        templateChangeDiv.style.display = 'block';
    } else {
        templateChangeDiv.style.display = 'none';
    }
});

// Toggle page status
function togglePageStatus(pageId) {
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

// Delete page confirmation
function deletePage(pageId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/pages/${pageId}`;
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

