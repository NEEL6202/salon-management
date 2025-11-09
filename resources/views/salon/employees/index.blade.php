@extends('layouts.modern')

@section('title', 'Employee Management')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Employee Management</h1>
        <p class="page-subtitle">Manage your salon team</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('salon.employees.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Employee
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $employees->total() }}</h4>
                            <small>Total Employees</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $employees->where('status', 'active')->count() }}</h4>
                            <small>Active Employees</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $employees->whereIn('roles.0.name', ['manager'])->count() }}</h4>
                            <small>Managers</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $employees->whereIn('roles.0.name', ['employee'])->count() }}</h4>
                            <small>Staff Members</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('salon.employees.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by name, email, or phone">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">All Roles</option>
                                <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Employees List</h5>
                </div>
                <div class="card-body">
                    @if($employees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Contact</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($employee->avatar)
                                                        <img src="{{ Storage::url($employee->avatar) }}" 
                                                             alt="{{ $employee->name }}" 
                                                             class="rounded-circle me-3" 
                                                             width="48" height="48">
                                                    @else
                                                        <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 48px; height: 48px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $employee->name }}</div>
                                                        <small class="text-muted">{{ $employee->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{ $employee->phone ?: 'No phone' }}</div>
                                                    <small class="text-muted">{{ $employee->gender ? ucfirst($employee->gender) : 'Not specified' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @foreach($employee->roles as $role)
                                                    <span class="badge bg-{{ $role->name === 'manager' ? 'info' : 'secondary' }}">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($employee->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $employee->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('salon.employees.show', $employee) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('salon.employees.edit', $employee) }}" 
                                                       class="btn btn-sm btn-outline-secondary" 
                                                       title="Edit Employee">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete Employee"
                                                            onclick="confirmDelete('{{ $employee->id }}', '{{ $employee->name }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $employees->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No employees found</h5>
                            <p class="text-muted">Start by adding your first employee to manage your salon operations.</p>
                            <a href="{{ route('salon.employees.create') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Add First Employee
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="employeeName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(employeeId, employeeName) {
    document.getElementById('employeeName').textContent = employeeName;
    document.getElementById('deleteForm').action = `/salon/employees/${employeeId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush 
