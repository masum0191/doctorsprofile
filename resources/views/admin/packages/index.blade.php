@extends('layouts.supperadmin')
@section('title', 'Package Management')

@section('content')
<style>
    /* Modern Package Management Design */
    .page-header {
        background: linear-gradient(135deg, rgba(49, 128, 105, 0.05) 0%, rgba(49, 128, 105, 0.02) 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(49, 128, 105, 0.1);
    }

    .package-feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        margin: 0.5rem 0;
    }

    .feature-item {
        font-size: 0.8rem;
    }

    .feature-item i {
        color: #318069;
        font-size: 0.75rem;
        margin-right: 0.5rem;
    }

    .price-tag {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem;
        text-align: center;
        transition: all 0.2s ease;
        min-width : 100px;
    }

    .price-tag:hover {
        border-color: #318069;
        transform: translateY(-2px);
    }

    .price-tag .period {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .price-tag .amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0.25rem 0;
    }

    .price-tag .discount {
        font-size: 0.75rem;
        color: #10B981;
        font-weight: 600;
    }

    .visibility-indicator {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        gap: 0.5rem;
    }

    .visibility-indicator.visible {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .visibility-indicator.hidden {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .visibility-indicator .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .visibility-indicator.visible .dot {
        background: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .visibility-indicator.hidden .dot {
        background: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }

    .package-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .package-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(49, 128, 105, 0.1) 0%, rgba(49, 128, 105, 0.05) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #318069;
        font-size: 1.25rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: #f9fafb;
        color: #374151;
        transform: translateY(-1px);
    }

    .action-btn.edit:hover {
        border-color: #3B82F6;
        color: #3B82F6;
    }

    .action-btn.delete:hover {
        border-color: #ef4444;
        color: #ef4444;
    }

    /* Enhanced Table */
    .packages-table {
        --table-border: 1px solid #f1f5f9;
        border-collapse: separate;
        border-spacing: 0;
    }

    .packages-table thead {
        background: #f8fafc;
    }

    .packages-table thead th {
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .packages-table tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        border-bottom: var(--table-border);
        background: white;
        transition: background-color 0.2s ease;
    }

    .packages-table tbody tr {
        transition: all 0.2s ease;
    }

    .packages-table tbody tr:hover {
        background: #f8fafc;
    }

    .packages-table tbody tr:hover td {
        background: #f8fafc;
    }

    .packages-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Filter Controls */
    .filter-controls {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }

    .filter-select {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.85rem;
        background: white;
        min-width: 140px;
    }

    /* Status Tags */
    .status-tag {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        gap: 0.375rem;
    }

    .status-tag.active {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-tag.inactive {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 2rem;
        text-align: center;
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(49, 128, 105, 0.1) 0%, rgba(49, 128, 105, 0.05) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: #318069;
        font-size: 2rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .packages-table {
            display: block;
        }

        .packages-table thead {
            display: none;
        }

        .packages-table tbody,
        .packages-table tr,
        .packages-table td {
            display: block;
            width: 100%;
        }

        .packages-table tr {
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .packages-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .packages-table td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select {
            width: 100%;
        }
    }
</style>

<div class="pb-3">
    <!-- Filter Controls -->
    <div class="filter-controls">
        <div class="filter-group">
            <span class="filter-label">Status:</span>
            <select class="filter-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        {{-- <div class="ms-auto">
            <div class="d-flex gap-2">
                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Package
                </a>
            </div>
        </div> --}}
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Packages Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($packages->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h5 class="mb-2">No Packages Found</h5>
                    <p class="text-muted mb-4">Create your first subscription package to get started</p>
                    {{-- <a href="{{ route('packages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Package
                    </a> --}}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table packages-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Package</th>
                                <th>Features</th>
                                <th>Pricing</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $package)
                                <tr>
                                    <td class="ps-4" data-label="Package">
                                        <div class="package-header">
                                            <div class="package-icon">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $package->name }}</div>
                                                <!-- @if($package->description)
                                                <div class="text-muted small mt-1">
                                                    {{ \Illuminate\Support\Str::limit($package->description, 60) }}
                                                </div>
                                                @endif -->
                                            </div>
                                        </div>
                                    </td>

                                    <td data-label="Features">
                                        <div class="package-feature-grid">
                                            <div class="feature-item">
                                                <i class="fas fa-database"></i>
                                                <span>{{ $package->storage_gb }} GB</span>
                                            </div>
                                            @if($package->max_doctors)
                                            <div class="feature-item">
                                                <i class="fas fa-user-md"></i>
                                                <span>{{ $package->max_doctors }} Doctors</span>
                                            </div>
                                            @endif
                                            @if($package->max_patients)
                                            <div class="feature-item">
                                                <i class="fas fa-users"></i>
                                                <span>{{ $package->max_patients }} Patients</span>
                                            </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td data-label="Pricing">
                                        <div class="d-flex gap-2">
                                            <div class="price-tag">
                                                <div class="period">Monthly</div>
                                                <div class="amount">{{ $companysetting->currency }}{{ number_format($package->price_monthly, 0) }}</div>
                                               @if($package->price_yearly && $package->price_monthly > 0)
    <div class="discount">
        Save {{ number_format((1 - ($package->price_yearly / ($package->price_monthly * 12))) * 100, 0) }}%
    </div>
@endif
                                            </div>
                                            @if($package->price_yearly)
                                            <div class="price-tag">
                                                <div class="period">Yearly</div>
                                                <div class="amount">{{ $companysetting->currency }}{{ number_format($package->price_yearly, 0) }}</div>
                                                <div class="text-muted small">/year</div>
                                            </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td data-label="Status">
                                        @if($package->is_visible)
                                        <span class="visibility-indicator visible">
                                            <span class="dot"></span>
                                            Active
                                        </span>
                                        @else
                                        <span class="visibility-indicator hidden">
                                            <span class="dot"></span>
                                            Hidden
                                        </span>
                                        @endif
                                    </td>

                                    <td class="text-end pe-4" data-label="Actions">
                                        <div class="d-flex justify-content-end gap-2">


                                            <a href="{{ route('packages.edit', $package) }}"
                                               class="act-btn edit-btn"
                                               title="Edit Package">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form action="{{ route('packages.destroy', $package) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirmDeletePackage('{{ addslashes($package->name) }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="act-btn delete-btn" title="Delete Package">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if(!$packages->isEmpty())
        <div class="card-footer border-top bg-white py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <span class="fw-semibold">{{ $packages->count() }}</span> packages found
                </div>
                @if(method_exists($packages, 'links'))
                <div>
                    <nav aria-label="Package pagination">
                        <ul class="pagination pagination-sm mb-0">
                            {{ $packages->links('pagination::bootstrap-5') }}
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function confirmDeletePackage(packageName) {
    return confirm(`Are you sure you want to delete "${packageName}"? This action cannot be undone.`);
}

document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to rows
    const rows = document.querySelectorAll('.packages-table tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.05)';
        });

        row.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });

    // Toggle visibility with loading state
    const toggleButtons = document.querySelectorAll('.action-buttons form button[title*="Hide"], .action-buttons form button[title*="Show"]');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const icon = this.querySelector('i');
            const originalClass = icon.className;

            // Show loading state
            icon.className = 'fas fa-spinner fa-spin';

            // Submit form after slight delay to show spinner
            setTimeout(() => {
                this.closest('form').submit();
            }, 100);
        });
    });

    // Filter functionality
    const statusFilter = document.querySelector('select.filter-select');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('.packages-table tbody tr');

            rows.forEach(row => {
                const statusCell = row.querySelector('.visibility-indicator');
                if (status === '' ||
                    (status === 'active' && statusCell.classList.contains('visible')) ||
                    (status === 'inactive' && statusCell.classList.contains('hidden'))) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Sort functionality
    const sortFilter = document.querySelectorAll('select.filter-select')[1];
    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            // In a real app, this would make an API call or reload the page
            // For demo, we'll just show a message
            alert('Sorting would be implemented here');
        });
    }
});
</script>
@endsection
