@extends('layouts.admin')
@section('title', 'Testimonials')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #2a6d5a;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --primary-hover: rgba(49, 128, 105, 0.15);
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary);
    }

    .header-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .header-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Filter Button */
    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(49, 128, 105, 0.15);
        border-color: var(--primary);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        border-radius: 4px 0 0 4px;
    }

    .stat-title {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .stat-subtitle {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    /* Table Container */
    .table-container {
        background: white;
        border: 1px solid rgba(49, 128, 105, 0.15);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .table-header {
        background: var(--primary-soft);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--primary-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-count {
        font-size: 0.875rem;
        color: #6b7280;
        background: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        border: 1px solid var(--primary-light);
    }

    /* Table Styles */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: var(--primary-soft);
        color: #374151;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1rem;
        border-bottom: 1px solid var(--primary-light);
        text-align: left;
    }

    .custom-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .custom-table tbody tr:last-child {
        border-bottom: none;
    }

    .custom-table tbody td {
        padding: 1rem 1rem;
        color: #374151;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    /* Patient Avatar */
    .patient-avatar {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: var(--primary-light);
        flex-shrink: 0;
    }

    .patient-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-weight: 600;
        font-size: 1rem;
        background: var(--primary-light);
    }

    .patient-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Rating Stars */
    .rating-stars {
        display: flex;
        gap: 0.125rem;
    }

    .star-filled {
        color: #fbbf24;
    }

    .star-empty {
        color: #d1d5db;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-verified {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-unverified {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Testimonial Content */
    .testimonial-content {
        max-width: 300px;
        line-height: 1.5;
    }

    .testimonial-excerpt {
        color: #374151;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .testimonial-full {
        color: #6b7280;
        font-size: 0.75rem;
        line-height: 1.5;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.75rem;
        background: white;
        border: 1px solid #e5e7eb;
    }

    .btn-icon:hover {
        background: #f9fafb;
    }

    .btn-edit:hover {
        color: var(--primary);
        border-color: var(--primary);
    }

    .btn-delete:hover {
        color: #dc2626;
        border-color: #dc2626;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-add {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-add:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 128, 105, 0.2);
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--primary);
        font-size: 1.5rem;
    }

    .empty-state h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 0.875rem;
        max-width: 300px;
        margin: 0 auto 1.5rem;
    }

    /* Pagination */
    .pagination-container {
        padding: 1.5rem;
        border-top: 1px solid #f3f4f6;
        background: white;
        display: flex;
        justify-content: center;
    }

    /* Modal Styling */
    .filter-modal .modal-dialog {
        max-width: 500px;
    }

    .filter-modal .modal-content {
        border-radius: 12px;
        border: 1px solid rgba(49, 128, 105, 0.15);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .filter-modal .modal-header {
        background: var(--primary);
        color: white;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .filter-modal .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-modal .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .filter-modal .btn-close:hover {
        opacity: 1;
    }

    .filter-modal .modal-body {
        padding: 1.5rem;
    }

    .filter-modal .modal-footer {
        border-top: 1px solid #f3f4f6;
        padding: 1rem 1.5rem;
        background: var(--primary-soft);
    }

    /* View Testimonial Modal */
    .view-modal .modal-dialog {
        max-width: 600px;
    }

    .view-modal .modal-content {
        border-radius: 12px;
        border: 1px solid rgba(49, 128, 105, 0.15);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .view-modal .modal-header {
        background: var(--primary);
        color: white;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .view-modal .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-modal .modal-body {
        padding: 1.5rem;
    }

    .view-modal .modal-footer {
        border-top: 1px solid #f3f4f6;
        padding: 1rem 1.5rem;
        background: var(--primary-soft);
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    .form-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #111827;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(49, 128, 105, 0.1);
        outline: none;
    }

    /* Active Filters Indicator */
    .active-filters {
        background: var(--primary-soft);
        border: 1px solid var(--primary-light);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-size: 0.875rem;
    }

    .active-filters i {
        color: var(--primary);
    }

    .filter-tag {
        background: white;
        border: 1px solid var(--primary-light);
        border-radius: 16px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .filter-tag .remove {
        cursor: pointer;
        margin-left: 0.25rem;
    }

    .filter-tag .remove:hover {
        color: #dc2626;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table-container {
            overflow-x: auto;
        }

        .custom-table {
            min-width: 800px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: flex-end;
        }
    }
</style>

<div class="">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-comment-medical me-2"></i>
                    Patient Testimonials
                </h1>

            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn-filter" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i>
                    Filter Testimonials
                </button>
                @if(auth()->user()->role_id === 1)
                    <a href="{{ route('admin.testimonials.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Add New
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @php
        $totalTestimonials = $testimonials->total();
        $verifiedCount = $testimonials->where('verified', true)->count();
        $averageRating = $testimonials->avg('rating');
        $unverifiedCount = $totalTestimonials - $verifiedCount;
    @endphp



    <!-- Active Filters Indicator -->
    @if(request()->hasAny(['q', 'verified', 'rating']))
    <div class="active-filters">
        <i class="fas fa-filter"></i>
        <span class="me-2">Active filters:</span>

        @if(request('q'))
            <span class="filter-tag">
                Search: "{{ request('q') }}"
                <a href="{{ route('admin.testimonials.index', array_merge(request()->except('q'), ['page' => 1])) }}" class="remove">
                    <i class="fas fa-times"></i>
                </a>
            </span>
        @endif

        @if(request('verified') === '1')
            <span class="filter-tag">
                Verified Only
                <a href="{{ route('admin.testimonials.index', array_merge(request()->except('verified'), ['page' => 1])) }}" class="remove">
                    <i class="fas fa-times"></i>
                </a>
            </span>
        @elseif(request('verified') === '0')
            <span class="filter-tag">
                Unverified Only
                <a href="{{ route('admin.testimonials.index', array_merge(request()->except('verified'), ['page' => 1])) }}" class="remove">
                    <i class="fas fa-times"></i>
                </a>
            </span>
        @endif

        @if(request('rating'))
            <span class="filter-tag">
                {{ request('rating') }} Stars
                <a href="{{ route('admin.testimonials.index', array_merge(request()->except('rating'), ['page' => 1])) }}" class="remove">
                    <i class="fas fa-times"></i>
                </a>
            </span>
        @endif

        <a href="{{ route('admin.testimonials.index') }}" class="ms-auto text-primary text-decoration-none">
            Clear all filters
        </a>
    </div>
    @endif

    <!-- Testimonials Table -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-comments me-2"></i>
                Testimonials List
            </h3>
            <div class="table-count">
                {{ $testimonials->total() }} Total • {{ $testimonials->count() }} Showing
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Patient</th>
                        <th>Photo</th>
                        <th>Since</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Testimonial</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                    <tr>
                        <td>
                            <div class="fw-semibold text-center">
                                {{ $testimonial->order_column }}
                            </div>
                        </td>

                        <td>
                            <div class="patient-info">

                                <div>
                                    <div class="fw-semibold">{{ $testimonial->patient_name }}</div>
                                    @if($testimonial->patient_info)
                                        <div class="text-muted small">{{ $testimonial->patient_info }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($testimonial->photo)
                                <div class="d-flex justify-content-center">
                                    <img src="{{ url($testimonial->photo) }}"
                                         alt="Photo"
                                         class="rounded"
                                         style="width: 48px; height: 48px; object-fit: cover;"
                                         onerror="this.style.display='none'">
                                </div>
                            @else
                                <div class="text-center">
                                    <i class="fas fa-user text-muted"></i>
                                    <div class="text-muted small mt-1">No photo</div>
                                </div>
                            @endif
                        </td>

                        <td>
                            @if($testimonial->since)
                                <div class="fw-semibold">{{ $testimonial->since }}</div>
                                <div class="text-muted small">Member since</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            <div class="rating-stars" title="{{ $testimonial->rating }} stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $testimonial->rating)
                                        <i class="fas fa-star star-filled"></i>
                                    @else
                                        <i class="far fa-star star-empty"></i>
                                    @endif
                                @endfor
                                <div class="text-muted small mt-1">{{ $testimonial->rating }}.0</div>
                            </div>
                        </td>

                        <td>
                            @if($testimonial->verified)
                                <span class="status-badge badge-verified">
                                    <i class="fas fa-check-circle"></i>
                                    Verified
                                </span>
                            @else
                                <span class="status-badge badge-unverified">
                                    <i class="fas fa-clock"></i>
                                    Unverified
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="testimonial-content">
                                <div class="testimonial-excerpt">
                                    {{ Str::limit($testimonial->content, 100) }}
                                </div>
                                @if(strlen($testimonial->content) > 100)
                                    <button class="btn btn-link btn-sm p-0 text-primary"
                                            onclick="viewFullTestimonial('{{ addslashes($testimonial->content) }}', '{{ $testimonial->patient_name }}')">
                                        Read more
                                    </button>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="action-buttons">
                                @if(auth()->user()->role_id === 1)
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                       class="btn-icon btn-edit"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif


                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>


                                <button class="btn-icon"
                                        onclick="toggleVerification({{ $testimonial->id }}, {{ $testimonial->verified ? 'true' : 'false' }})"
                                        title="{{ $testimonial->verified ? 'Unverify' : 'Verify' }}">
                                    <i class="fas {{ $testimonial->verified ? 'fa-times' : 'fa-check' }}"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-comment-slash"></i>
                                </div>
                                <h5>No testimonials found</h5>
                                <p>
                                    @if(request()->hasAny(['q', 'verified', 'rating']))
                                        Try adjusting your filters or clear them to see all testimonials
                                    @else
                                        Start by adding your first patient testimonial
                                    @endif
                                </p>
                                @if(auth()->user()->role_id === 1 && !request()->hasAny(['q', 'verified', 'rating']))
                                    <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        Add Testimonial
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($testimonials->hasPages())
        <div class="pagination-container">
            {{ $testimonials->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade filter-modal" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filter Testimonials
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.testimonials.index') }}">
                <input type="hidden" name="page" value="1">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Search Patient</label>
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               class="form-control"
                               placeholder="Enter patient name...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Verification Status</label>
                        <select name="verified" class="form-select">
                            <option value="all" {{ request('verified') == 'all' || !request('verified') ? 'selected' : '' }}>All Status</option>
                            <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Verified Only</option>
                            <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Unverified Only</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>★★★★★ (5 Stars)</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>★★★★☆ (4 Stars)</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>★★★☆☆ (3 Stars)</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ (2 Stars)</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ (1 Star)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Highest Rating</option>
                            <option value="rating_asc" {{ request('sort') == 'rating_asc' ? 'selected' : '' }}>Lowest Rating</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary">
                        Reset
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter me-2"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Full Testimonial Modal -->
<div class="modal fade view-modal" id="viewTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-comment-medical me-2"></i>
                    Testimonial Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6 id="modalPatientName" class="fw-semibold"></h6>
                </div>
                <div class="testimonial-content-full" id="modalTestimonialContent" style="line-height: 1.6;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modals
        const filterModal = new bootstrap.Modal(document.getElementById('filterModal'));
        const viewModal = new bootstrap.Modal(document.getElementById('viewTestimonialModal'));

        // Function to view full testimonial
        window.viewFullTestimonial = function(content, patientName) {
            document.getElementById('modalPatientName').textContent = patientName + ' says:';
            document.getElementById('modalTestimonialContent').textContent = content;
            viewModal.show();
        };

        // Confirm delete
        window.confirmDelete = function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
                event.target.closest('form').submit();
            }
            return false;
        };

        // Toggle verification
        window.toggleVerification = function(id, isVerified) {
            const action = isVerified ? 'unverify' : 'verify';
            const actionText = isVerified ? 'Unverify' : 'Verify';

            if (confirm(`${actionText} this testimonial?`)) {
                fetch(`/admin/testimonials/${id}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated status
                        location.reload();
                    } else {
                        alert('Failed to update testimonial status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        };

        // Keyboard shortcut for filter modal (Ctrl/Cmd + F)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                filterModal.show();
            }
        });

        // Animate table rows on load
        const tableRows = document.querySelectorAll('.custom-table tbody tr');
        tableRows.forEach((row, index) => {
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });

        // Set default styles for animation
        document.querySelectorAll('.custom-table tbody tr').forEach(row => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = 'all 0.3s ease';
        });

        // Animate stats cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        document.querySelectorAll('.stat-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.3s ease';
        });
    });
</script>
@endsection
