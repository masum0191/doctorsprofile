@extends('layouts.supperadmin')

@section('title', 'User Role Management')
@section('page-title', 'User Roles')
@section('page-subtitle', 'Manage central users, access roles, and active status.')

@section('content')
    <div class="row g-3 mb-3">
        @foreach($roles as $role => $label)
            <div class="col-sm-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <div class="text-muted small">{{ $label }}</div>
                        <div class="fs-4 fw-bold">{{ number_format((int) ($roleCounts[$role] ?? 0)) }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.users.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Search</label>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control"
                           placeholder="Name, email, or mobile">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Role</label>
                    <select name="role" class="form-select">
                        <option value="">All roles</option>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['role'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="1" @selected(($filters['status'] ?? '') === '1')>Active</option>
                        <option value="0" @selected(($filters['status'] ?? '') === '0')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-fill" type="submit">
                        <i class="ri-search-line me-1"></i>Filter
                    </button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-light border">Reset</a>
                    <a href="{{ route('superadmin.users.create') }}" class="btn btn-success">
                        <i class="ri-user-add-line me-1"></i>Add
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">Users</h5>
            <span class="text-muted small">{{ $users->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Tenant</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <div class="text-muted small">{{ $user->email }}</div>
                                @if($user->mobile)
                                    <div class="text-muted small">{{ $user->mobile }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $roles[$user->role] ?? ucfirst($user->role ?? 'Unknown') }}
                                </span>
                            </td>
                            <td>
                                @if($user->tenant)
                                    <span class="small">{{ data_get($user->tenant->data, 'name') ?? $user->tenant_id }}</span>
                                @elseif($user->tenant_id)
                                    <span class="small">{{ $user->tenant_id }}</span>
                                @else
                                    <span class="text-muted small">None</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ (int) $user->status === 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ (int) $user->status === 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ optional($user->created_at)->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-edit-line"></i>
                                    </a>

                                    <form action="{{ route('superadmin.users.status', $user) }}" method="POST"
                                          onsubmit="return confirm('Change this user status?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ (int) $user->status === 1 ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-sm {{ (int) $user->status === 1 ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                            <i class="{{ (int) $user->status === 1 ? 'ri-user-unfollow-line' : 'ri-user-follow-line' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
