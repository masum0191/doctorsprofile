@extends('layouts.supperadmin')
@section('title', 'Doctors Management Dashboard')

@section('content')

<style>
    :root {
        --primary: #318069;
        --primary-dark: #276854;
        --primary-light: #e8f5f0;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --secondary: #FFC107;
        --success: #10B981;
        --danger: #EF4444;
        --warning: #F59E0B;
        --info: #3B82F6;
        --dark: #1F2937;
        --gray: #6B7280;
        --gray-light: #F9FAFB;
        --border: #E2E8F0;
    }

    /* Stats Cards */
    .stats-card {
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        border: none;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255,255,255,0.3);
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
    }
    
    .stats-card .icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
    }
    
    .stats-card .stats-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }
    
    /* Search Box */
    .search-box {
        position: relative;
    }
    
    .search-box .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        font-size: 0.9rem;
    }
    
    .search-box input {
        padding-left: 2.5rem;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: white;
        transition: all 0.2s;
    }
    
    .search-box input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }
    
    /* Enhanced Table */
    .doctors-table {
        border-radius: 16px;
        overflow: hidden;
    }
    
    .doctors-table thead th {
        background: var(--gray-light);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        padding: 1rem 1rem;
        border-bottom: 1px solid var(--border);
    }
    
    .doctors-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--border);
    }
    
    .doctors-table tbody tr:hover {
        background: var(--primary-soft);
    }
    
    .doctors-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    /* Doctor Avatar */
    .doctor-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }
    
    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #065F46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #991B1B;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    /* Domain Link */
    .domain-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        background: var(--primary-soft);
        border-radius: 20px;
        font-size: 0.75rem;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .domain-link:hover {
        background: var(--primary-light);
        color: var(--primary-dark);
    }
    
    /* Action Buttons */
    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid var(--border);
        background: white;
        color: var(--gray);
        cursor: pointer;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .action-btn.feature-btn {
        border-color: var(--warning);
        color: var(--warning);
    }
    
    .action-btn.feature-btn:hover {
        background: var(--warning);
        color: white;
        border-color: var(--warning);
    }
    
    .action-btn.feature-active {
        background: var(--warning);
        color: white;
        border-color: var(--warning);
    }
    
    .action-btn.delete-btn {
        border-color: var(--danger);
        color: var(--danger);
    }
    
    .action-btn.delete-btn:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
    }
    
    /* Filter Badges */
    .filter-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-badge a {
        color: var(--danger);
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    
    .filter-badge a:hover {
        opacity: 1;
    }
    
    /* Charts */
    .chart-bar {
        transition: height 0.3s ease;
        border-radius: 8px 8px 0 0;
    }
    
    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }
    
    .empty-state-icon i {
        font-size: 2rem;
        color: var(--primary);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .stats-card .stats-value {
            font-size: 1.5rem;
        }
        
        .stats-card .icon-circle {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .doctors-table thead {
            display: none;
        }
        
        .doctors-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem;
        }
        
        .doctors-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px dashed var(--border);
        }
        
        .doctors-table tbody td:last-child {
            border-bottom: none;
        }
        
        .doctors-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary);
            font-size: 0.75rem;
        }
    }
</style>

<div class="container-fluid">
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold text-gray-800 mb-1">
                Doctors Management
            </h1>
            <p class="text-muted small mb-0">Manage all doctors, their domains, and account status</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            <a href="{{ url('doctor/create') }}" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-plus me-1"></i> Add Doctor
            </a>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <!-- <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-gradient-primary text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-white-50 mb-1">Total Doctors</p>
                        <div class="stats-value">{{ $doctors->count() }}</div>
                        <div class="small text-white-70 mt-2">
                            <i class="fas fa-arrow-up me-1"></i> 12% growth
                        </div>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-user-md"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-gradient-success text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-white-50 mb-1">Active Doctors</p>
                        <div class="stats-value">{{ $doctors->where('status', 1)->count() }}</div>
                        <div class="small text-white-70 mt-2">
                            <i class="fas fa-arrow-up me-1"></i> 8% growth
                        </div>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-gradient-warning text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-white-50 mb-1">Inactive Doctors</p>
                        <div class="stats-value">{{ $doctors->where('status', 0)->count() }}</div>
                        <div class="small text-white-70 mt-2">
                            <i class="fas fa-arrow-down me-1"></i> 3% decrease
                        </div>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-pause-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-gradient-info text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-white-50 mb-1">Total Domains</p>
                        <div class="stats-value">{{ $doctors->sum(fn($doctor) => optional($doctor->domains)->count() ?? 0) }}</div>
                        <div class="small text-white-70 mt-2">
                            <i class="fas fa-arrow-up me-1"></i> 15% growth
                        </div>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Search Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form id="searchForm" method="GET" action="{{ url('superadmin/dashboard') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name, email, mobile, or ID..." value="{{ request()->search }}"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            @if (request()->hasAny(['search', 'status', 'domain', 'start_date', 'end_date']))
                                <a href="{{ url('superadmin/dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Active Filters Display -->
                @if (request()->hasAny(['search', 'status', 'domain', 'start_date', 'end_date']))
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span class="text-muted small me-2">Active filters:</span>
                                @if (request()->search)
                                    <span class="filter-badge">
                                        <i class="fas fa-search"></i> {{ request()->search }}
                                        <a href="{{ url()->current() }}?{{ http_build_query(request()->except('search')) }}" class="text-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                @if (request()->status)
                                    <span class="filter-badge">
                                        <i class="fas fa-circle"></i> Status: {{ request()->status == 1 ? 'Active' : 'Inactive' }}
                                        <a href="{{ url()->current() }}?{{ http_build_query(request()->except('status')) }}" class="text-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                @if (request()->domain)
                                    <span class="filter-badge">
                                        <i class="fas fa-globe"></i> Domain: {{ request()->domain }}
                                        <a href="{{ url()->current() }}?{{ http_build_query(request()->except('domain')) }}" class="text-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Doctors Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-list me-2 text-primary"></i>
                    All Doctors
                </h6>
                <span class="badge bg-light text-dark">
                    <i class="fas fa-users me-1"></i> {{ $doctors->count() }} Total
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            @if ($doctors->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h5 class="fw-semibold text-gray-800 mb-2">No Doctors Found</h5>
                    <p class="text-muted mb-3">Get started by registering your first doctor</p>
                    <a href="{{ url('doctor/create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Register Doctor
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="doctors-table table mb-0">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Domain</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $doctor)
                                <tr>
                                    <td data-label="Doctor">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="doctor-avatar">
                                                {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-gray-900">{{ $doctor->name }}</div>
                                                <div class="small text-muted">
                                                    ID: DR{{ str_pad($doctor->id, 4, '0', STR_PAD_LEFT) }}
                                                </div>
                                                @if ($doctor->email)
                                                    <div class="small text-muted mt-1">{{ $doctor->email }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Domain">
                                        @if ($doctor->tenant_id)
                                            @php
                                                $tenant = App\Models\Tenant::with('domains')->find($doctor->tenant_id);
                                            @endphp
                                            @if ($tenant && $tenant->domains->isNotEmpty())
                                                @foreach ($tenant->domains->take(1) as $domain)
                                                    <a href="http://{{ $domain->domain }}" target="_blank" class="domain-link">
                                                        <i class="fas fa-external-link-alt"></i>
                                                        {{ Str::limit($domain->domain, 25) }}
                                                    </a>
                                                @endforeach
                                                @if ($tenant->domains->count() > 1)
                                                    <small class="text-muted ms-1">+{{ $tenant->domains->count() - 1 }} more</small>
                                                @endif
                                            @else
                                                <span class="text-muted small">No domain</span>
                                            @endif
                                        @else
                                            <span class="text-muted small">No domain</span>
                                        @endif
                                    </td>
                                    <td data-label="Status">
                                        @if ($doctor->status == 1)
                                            <span class="status-badge status-active">
                                                <i class="fas fa-circle" style="font-size: 6px;"></i> Active
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-circle" style="font-size: 6px;"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td data-label="Created">
                                        <div class="small fw-medium">{{ $doctor->created_at->format('M d, Y') }}</div>
                                        <div class="small text-muted">{{ $doctor->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td data-label="Actions" class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if ($doctor->status == 0)
                                                <form action="{{ route('tenant.toggle', ['id' => $doctor->id, 'status' => 1]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="action-btn feature-btn" title="Activate">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('tenant.toggle', ['id' => $doctor->id, 'status' => 0]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="action-btn feature-btn" title="Suspend">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('doctor.feature.toggle', $doctor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="action-btn {{ $doctor->feature ? 'feature-active' : 'feature-btn' }}" title="{{ $doctor->feature ? 'Remove Featured' : 'Make Featured' }}">
                                                    <i class="fas {{ $doctor->feature ? 'fa-star' : 'fa-star-half-alt' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="action-btn delete-btn" onclick="confirmDelete(@js($doctor->id), @js($doctor->name))" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        @if (!$doctors->isEmpty() && method_exists($doctors, 'links') && $doctors->hasPages())
            <div class="card-footer bg-white border-top-0 py-3 px-4">
                <div class="d-flex justify-content-end">
                    {{ $doctors->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Stats Row -->
   

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom p-4">
                    <h5 class="modal-title fw-semibold">
                        <i class="fas fa-filter me-2 text-primary"></i>
                        Filter Doctors
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="filterForm" method="GET" action="{{ url('superadmin/dashboard') }}">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Date Range</label>
                            <div class="input-group">
                                <input type="date" name="start_date" class="form-control" value="{{ request()->start_date }}" placeholder="Start Date">
                                <span class="input-group-text">to</span>
                                <input type="date" name="end_date" class="form-control" value="{{ request()->end_date }}" placeholder="End Date">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="actualDoctorId">
                    <div class="modal-header border-bottom p-4">
                        <h5 class="modal-title text-danger fw-semibold">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Delete Confirmation
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning border-0 py-3 mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            This action cannot be undone. All doctor data will be permanently deleted.
                        </div>
                        <p id="deleteText" class="mb-3"></p>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Type <code id="doctorIdDisplay" class="text-danger">DOCTOR-ID</code> to confirm:
                            </label>
                            <input type="text" id="doctorIdInput" class="form-control" placeholder="Enter doctor ID" autocomplete="off">
                            <div class="invalid-feedback small" id="idError">Doctor ID does not match.</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmDelete">
                            <label class="form-check-label small" for="confirmDelete">
                                I understand this action is permanent
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="deleteButton" disabled>
                            <i class="fas fa-trash me-1"></i> Delete Permanently
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const filterModal = new bootstrap.Modal(document.getElementById('filterModal'));

    // Search form debouncing
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });
    }

    // Delete confirmation functionality
    const doctorIdInput = document.getElementById('doctorIdInput');
    const deleteButton = document.getElementById('deleteButton');
    const idError = document.getElementById('idError');
    const doctorIdDisplay = document.getElementById('doctorIdDisplay');
    const confirmDeleteCheckbox = document.getElementById('confirmDelete');

    // Global confirmDelete function
    window.confirmDelete = function(id, name) {
        const form = document.getElementById('deleteForm');
        form.setAttribute('action', @json(route('tenant.destroy', ['id' => '__DOCTOR_ID__'])).replace('__DOCTOR_ID__', id));
        document.getElementById('actualDoctorId').value = id;

        // Update modal text
        document.getElementById('deleteText').innerHTML = `Delete <strong>Dr. ${name}</strong> permanently?`;
        doctorIdDisplay.textContent = `DR${String(id).padStart(4, '0')}`;

        // Reset inputs
        doctorIdInput.value = '';
        doctorIdInput.classList.remove('is-invalid', 'is-valid');
        confirmDeleteCheckbox.checked = false;
        deleteButton.disabled = true;
        idError.style.display = 'none';

        // Show modal
        deleteModal.show();

        // Focus on ID input
        setTimeout(() => {
            doctorIdInput.focus();
        }, 300);
    };

    // Validate ID input
    function validateDeleteForm() {
        const enteredId = doctorIdInput.value.trim();
        const actualId = document.getElementById('actualDoctorId').value;
        const doctorCode = `DR${String(actualId).padStart(4, '0')}`;
        const isCheckboxChecked = confirmDeleteCheckbox.checked;

        let isValid = true;
        let errorMessage = '';

        if (enteredId === '') {
            doctorIdInput.classList.remove('is-invalid', 'is-valid');
            isValid = false;
        } else if (enteredId !== doctorCode) {
            doctorIdInput.classList.remove('is-valid');
            doctorIdInput.classList.add('is-invalid');
            errorMessage = `ID must be exactly "${doctorCode}"`;
            isValid = false;
        } else {
            doctorIdInput.classList.remove('is-invalid');
            doctorIdInput.classList.add('is-valid');
        }

        if (errorMessage) {
            idError.textContent = errorMessage;
            idError.style.display = 'block';
        } else {
            idError.style.display = 'none';
        }

        deleteButton.disabled = !(isValid && isCheckboxChecked);
        return isValid;
    }

    // Event listeners
    doctorIdInput.addEventListener('input', validateDeleteForm);
    confirmDeleteCheckbox.addEventListener('change', validateDeleteForm);

    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        const enteredId = doctorIdInput.value.trim();
        const actualId = document.getElementById('actualDoctorId').value;
        const doctorCode = `DR${String(actualId).padStart(4, '0')}`;

        if (enteredId !== doctorCode || !confirmDeleteCheckbox.checked) {
            e.preventDefault();
            validateDeleteForm();
            doctorIdInput.focus();
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            filterModal.show();
        }

        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const modal = bootstrap.Modal.getInstance(openModal);
                modal.hide();
            }
        }
    });
});
</script>

@endsection
