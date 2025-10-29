@extends('layouts.modern')

@section('title', 'Landing Page Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-home me-2"></i>
                    Landing Page Management
                </h2>
                <button class="btn btn-primary" onclick="initializeSections()">
                    <i class="fas fa-plus me-2"></i>
                    Initialize Sections
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Landing Page Sections</h5>
                </div>
                <div class="card-body p-0">
                    @if($sections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Section</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sections as $section)
                                        <tr>
                                            <td>
                                                <strong>{{ ucwords(str_replace('_', ' ', $section->section_key)) }}</strong>
                                            </td>
                                            <td>{{ Str::limit($section->title, 50) ?? '-' }}</td>
                                            <td>
                                                @if($section->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $section->updated_at->diffForHumans() }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.landing-page.edit', $section->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.landing-page.toggle', $section->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-toggle-{{ $section->is_active ? 'on' : 'off' }}"></i>
                                                        {{ $section->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                            <h5>No sections found</h5>
                            <p class="text-muted">Click "Initialize Sections" to create default landing page sections.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function initializeSections() {
    if (confirm('This will create default landing page sections. Continue?')) {
        fetch('{{ route("admin.landing-page.initialize") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
}
</script>
@endsection
