@extends('layouts.supperadmin')
@section('title', 'Leads')

@section('content')

<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #276854;
        --primary-soft: rgba(49, 128, 105, 0.05);
    }

    /* Table Styles */
    .data-table {
        border-radius: 12px;
        overflow: hidden;
    }

    .data-table thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 1rem;
        border-bottom: 2px solid var(--primary-light);
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .data-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #1f2937;
        font-size: 0.875rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-new {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .status-contacted {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-converted {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    /* Source Badge */
    .source-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    .metric-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        padding: 1rem 1.1rem;
        height: 100%;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .metric-label {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    .metric-value {
        font-size: 1.7rem;
        line-height: 1;
        font-weight: 700;
        color: #111827;
    }

    /* Action Buttons */
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .action-btn.edit {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .action-btn.edit:hover {
        background: #f59e0b;
        color: white;
        transform: translateY(-2px);
    }

    .action-btn.delete {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .action-btn.delete:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    /* Modal Enhancement */
    .modal-content {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-radius: 0 0 16px 16px;
    }

    /* Form Enhancement */
    .form-label {
        font-weight: 600;
        font-size: 0.8rem;
        color: #374151;
        margin-bottom: 0.375rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
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
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .data-table thead {
            display: none;
        }
        
        .data-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
        }
        
        .data-table tbody td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px dashed #e5e7eb;
        }
        
        .data-table tbody td:last-child {
            border-bottom: none;
        }
        
        .data-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary);
            margin-right: 1rem;
            font-size: 0.8rem;
        }
    }
</style>

{{-- Page Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1 text-gray-800">
            <i class="ri-user-follow-line text-primary me-2"></i>
            Leads
        </h4>
        <p class="text-muted mb-0 small">Manage and track potential customers</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button class="btn btn-outline-primary d-inline-flex align-items-center gap-2 px-4"
                data-bs-toggle="modal"
                data-bs-target="#importLeadModal">
            <i class="ri-upload-2-line"></i>
            <span>Import CSV</span>
        </button>
        <button class="btn btn-primary d-inline-flex align-items-center gap-2 px-4" 
                data-bs-toggle="modal" 
                data-bs-target="#createLeadModal">
            <i class="ri-add-line"></i>
            <span>New Lead</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="metric-label">Total Leads</div>
            <div class="metric-value">{{ $leadStats['total'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="metric-label">New</div>
            <div class="metric-value">{{ $leadStats['new'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="metric-label">Contacted</div>
            <div class="metric-value">{{ $leadStats['contacted'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card">
            <div class="metric-label">Converted</div>
            <div class="metric-value">{{ $leadStats['converted'] }}</div>
        </div>
    </div>
</div>

{{-- Lead List Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom p-3">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-user-follow-line text-primary me-1"></i>
                Lead List
            </h6>
            <span class="badge bg-primary-soft text-primary px-3 py-1 rounded-pill">
                {{ $leadStats['total'] }} Leads
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table table mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px"># ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th style="width: 140px">Source</th>
                        <th style="width: 100px">Status</th>
                        <th style="width: 100px" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td data-label="# ID">
                            <span class="text-muted">#{{ $lead->id }}</span>
                        </td>
                        <td data-label="Name">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: var(--primary-light);">
                                    <i class="ri-user-line text-primary" style="font-size: 0.9rem;"></i>
                                </div>
                                <span class="fw-semibold text-gray-800">{{ $lead->name }}</span>
                            </div>
                        </td>
                        <td data-label="Phone">
                            <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-gray-700">
                                <i class="ri-phone-line me-1 text-muted"></i>
                                {{ $lead->phone ?? '—' }}
                            </a>
                        </td>
                        <td data-label="Email">
                            <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-gray-700">
                                <i class="ri-mail-line me-1 text-muted"></i>
                                {{ $lead->email ?? '—' }}
                            </a>
                        </td>
                        <td data-label="Source">
                            @if($lead->source)
                                <span class="source-badge">
                                    <i class="ri-global-line me-1" style="font-size: 0.7rem;"></i>
                                    {{ $lead->source }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td data-label="Status">
                            <span class="status-badge status-{{ $lead->status }}">
                                <i class="ri-checkbox-circle-fill" style="font-size: 0.6rem;"></i>
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td data-label="Actions" class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="action-btn edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editLeadModal{{ $lead->id }}"
                                        title="Edit Lead">
                                    <i class="ri-edit-line"></i>
                                </button>

                                <form method="POST"
                                      action="{{ route('superadmin.leads.destroy', $lead->id) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn delete border-0"
                                            onclick="return confirm('Are you sure you want to delete "{{ $lead->name }}" lead?')"
                                            title="Delete Lead">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- EDIT LEAD MODAL --}}
                    <div class="modal fade" id="editLeadModal{{ $lead->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('superadmin.leads.update', $lead->id) }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="modal-header">
                                        <div>
                                            <h5 class="modal-title mb-0">Edit Lead</h5>
                                            <small class="opacity-75">Update lead information</small>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-user-line me-1"></i> Full Name
                                                </label>
                                                <input name="name" value="{{ $lead->name }}" 
                                                       class="form-control" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-phone-line me-1"></i> Phone Number
                                                </label>
                                                <input name="phone" value="{{ $lead->phone }}" 
                                                       class="form-control" placeholder="+880xxxxxxxxx">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-mail-line me-1"></i> Email Address
                                                </label>
                                                <input name="email" value="{{ $lead->email }}" 
                                                       class="form-control" type="email" placeholder="lead@example.com">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-global-line me-1"></i> Lead Source
                                                </label>
                                                <input name="source" value="{{ $lead->source }}" 
                                                       class="form-control" placeholder="Website, Referral, FB">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ri-price-tag-3-line me-1"></i> Status
                                                </label>
                                                <select name="status" class="form-select">
                                                    <option value="new" @selected($lead->status == 'new')>🆕 New</option>
                                                    <option value="contacted" @selected($lead->status == 'contacted')>📞 Contacted</option>
                                                    <option value="converted" @selected($lead->status == 'converted')>✅ Converted</option>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">
                                                    <i class="ri-file-text-line me-1"></i> Notes
                                                </label>
                                                <textarea name="notes" rows="3" 
                                                          class="form-control" placeholder="Additional notes about this lead...">{{ $lead->notes }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="ri-save-3-line me-1"></i>
                                            Update Lead
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ri-user-follow-line"></i>
                                </div>
                                <h5 class="fw-semibold text-gray-800 mb-2">No Leads Found</h5>
                                <p class="text-muted mb-4">Get started by creating your first lead</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                                    <i class="ri-add-line me-1"></i> Create Lead
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CREATE LEAD MODAL --}}
<div class="modal fade" id="createLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('superadmin.leads.store') }}">
                @csrf
                
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Create New Lead</h5>
                        <small class="opacity-75">Add a new potential customer</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-user-line me-1"></i> Full Name
                            </label>
                            <input name="name" class="form-control" placeholder="John Doe" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-phone-line me-1"></i> Phone Number
                            </label>
                            <input name="phone" class="form-control" placeholder="+880xxxxxxxxx">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-mail-line me-1"></i> Email Address
                            </label>
                            <input name="email" class="form-control" type="email" placeholder="lead@example.com">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-global-line me-1"></i> Lead Source
                            </label>
                            <input name="source" class="form-control" placeholder="Website, Facebook, Referral">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-price-tag-3-line me-1"></i> Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="new">🆕 New</option>
                                <option value="contacted">📞 Contacted</option>
                                <option value="converted">✅ Converted</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                <i class="ri-file-text-line me-1"></i> Notes
                            </label>
                            <textarea name="notes" rows="3" class="form-control" 
                                      placeholder="Additional notes about this lead..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>
                        Create Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- IMPORT LEADS MODAL --}}
<div class="modal fade" id="importLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('superadmin.leads.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Import Leads From CSV</h5>
                        <small class="opacity-75">Supported columns: name, phone, email, source, status, notes</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-file-upload-line me-1"></i> CSV File
                        </label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv,text/csv" required>
                    </div>
                    <div class="rounded border bg-light p-3 small text-muted">
                        Required column: <strong>name</strong>.
                        Valid status values: <strong>new</strong>, <strong>contacted</strong>, <strong>converted</strong>.
                        Unknown or missing statuses are imported as <strong>new</strong>.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-upload-2-line me-1"></i>
                        Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Add data-label attributes for responsive table
    document.addEventListener('DOMContentLoaded', function() {
        const tableHeaders = document.querySelectorAll('.data-table thead th');
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (tableHeaders[index]) {
                    const headerText = tableHeaders[index].textContent.trim();
                    cell.setAttribute('data-label', headerText);
                }
            });
        });
    });
</script>
@endpush
