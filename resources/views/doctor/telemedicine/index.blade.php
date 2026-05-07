@extends('layouts.admin')
@section('title', 'Telemedicine Platforms')

@section('content')
<style>
    /* Enhanced Telemedicine Platforms Styles */
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    .telemedicine-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        background: white;
        overflow: hidden;
        transition: all 0.3s ease;
    }


    .table-container {
        border-radius: 12px;
        overflow: hidden;
    }

    .platforms-table {
        --bs-table-bg: transparent;
        margin-bottom: 0;
    }

    .platforms-table thead th {
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--primary-light);
        padding: 1rem 1.25rem;
        background: var(--primary-soft);
        white-space: nowrap;
    }

    .platforms-table tbody tr {
        border-bottom: 1px solid var(--primary-soft);
        transition: all 0.2s ease;
    }

    .platforms-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .platforms-table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    .platforms-table tbody tr:last-child {
        border-bottom: none;
    }

    .platform-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-active {
        background: rgba(49, 128, 105, 0.1);
        color: #065f46;
        border: 1px solid rgba(49, 128, 105, 0.2);
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .color-preview {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: inline-block;
        vertical-align: middle;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .icon-preview {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--primary-light);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--primary);
    }

    .filter-card {
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        background: white;
        padding: 1.3rem;
    }

    .form-control-enhanced {
        border: 1px solid rgba(49, 128, 105, 0.2);
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control-enhanced:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .select-enhanced {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23318069' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }

    .success-alert {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        color: #065f46;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .empty-state {
        padding: 3rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary);
        margin: 0 auto 1.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-table-action {
        padding: 0.375rem 0.875rem;
        font-size: 0.75rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: var(--primary-light);
        color: var(--primary);
        border: 1px solid rgba(49, 128, 105, 0.2);
    }

    .btn-edit:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .btn-delete:hover {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }

    .order-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--primary-light);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--primary);
        font-size: 0.875rem;
    }

    .header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--primary);
    }

    @media (max-width: 768px) {
        .table-container {
            overflow-x: auto;
        }

        .platforms-table {
            min-width: 700px;
        }

        .action-buttons {
            flex-direction: column;
            min-width: 100px;
        }
    }
</style>

<div class="">
    {{-- Header --}}
    <div class="header-section">
        <div class="header-title">
            <div class="header-icon">
                <i class="fas fa-video"></i>
            </div>
            <div>
                <h1 class="h3 mb-1 fw-bold">Telemedicine Platforms</h1>
            </div>
        </div>

        <a href="{{url('admin/profile/edit')}}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Add Platform</span>
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('ok'))
        <div class="success-alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-check-circle text-success"></i>
                <span class="fw-medium">{{ session('ok') }}</span>
            </div>
        </div>
    @endif

    {{-- Filters Card --}}
    <div class="filter-card mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold text-primary mb-2">Search Platform</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text"
                           id="q"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search by platform name..."
                           class="form-control form-control-enhanced border-start-0 ps-0" />
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold text-primary mb-2">Status</label>
                <select id="active"
                        name="active"
                        class="form-select form-control-enhanced select-enhanced">
                    <option value="all" @selected(request('active') === 'all' || request('active') === null)>All Status</option>
                    <option value="1" @selected(request('active') === '1')>Active Only</option>
                    <option value="0" @selected(request('active') === '0')>Inactive Only</option>
                </select>
            </div>

            <div class="col-md-4">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.telemedicine.index') }}" class="btn btn-primary-outline px-4">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Platforms Table Card --}}
    <div class="telemedicine-card">
     <div class="card-body p-0">
            <div class="table-container">
                <table class="table platforms-table">
                    <thead>
                        <tr>
                            <th class="ps-4">Order</th>
                            <th>Platform</th>
                            <th>Icon</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($platforms as $p)
                        <tr>
                            <td class="ps-4">
                                <div class="order-badge">
                                    {{ $p->order_column }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold text-primary mb-1">{{ $p->name }}</div>
                                @if($p->description)
                                    <small class="text-muted">{{ Str::limit($p->description, 40) }}</small>
                                @endif
                            </td>

                            <td>
                                @if($p->icon)
                                    <div class="icon-preview">
                                        <i class="{{ $p->icon }}"></i>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ $p->icon }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="color-preview" style="background: {{ $p->color }};"></span>
                                    <span class="text-monospace small">{{ $p->color }}</span>
                                </div>
                            </td>

                            <td>
                                @if($p->active)
                                    <span class="platform-badge badge-active">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="platform-badge badge-inactive">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="action-buttons">
                                    {{-- <a href="{{ route('doctor.telemedicine.edit', $p->id) }}"
                                       class="btn btn-table-action btn-edit">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a> --}}

                                    <form action="{{ route('admin.telemedicine.destroy', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this platform? This action cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-table-action btn-delete">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-video-slash"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">No telemedicine platforms found</h6>
                                    <p class="text-muted mb-3">Start by adding your first telemedicine platform</p>
                                    <a href="" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add Platform
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- @if($platforms->hasPages())
        <div class="card-footer border-top bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $platforms->firstItem() }} to {{ $platforms->lastItem() }} of {{ $platforms->total() }} entries
                </div>
                {{ $platforms->links() }}
            </div>
        </div>
        @endif --}}
    </div>

    {{-- Additional Information --}}
    @if($platforms->isNotEmpty())
    <div class="alert alert-primary-soft border-primary border mt-4">
        <div class="d-flex align-items-start gap-3">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-primary fs-5"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-semibold mb-2">About Telemedicine Platforms</h6>
                <p class="mb-0 small">These platforms will be available for patients to choose from when scheduling telemedicine appointments. Active platforms are visible to patients, while inactive ones are hidden. You can reorder platforms by editing their order values.</p>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus styles to search input
        const searchInput = document.getElementById('q');
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                this.parentElement.style.borderColor = 'var(--primary)';
                this.parentElement.style.boxShadow = '0 0 0 3px rgba(49, 128, 105, 0.1)';
            });

            searchInput.addEventListener('blur', function() {
                this.parentElement.style.borderColor = 'rgba(49, 128, 105, 0.2)';
                this.parentElement.style.boxShadow = 'none';
            });
        }

        // Auto-dismiss success message after 5 seconds
        const successAlert = document.querySelector('.success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 300);
            }, 5000);
        }

        // Table row hover effects
        const tableRows = document.querySelectorAll('.platforms-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(4px)';
            });

            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });
</script>
@endsection
