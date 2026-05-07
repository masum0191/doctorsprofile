@extends('layouts.supperadmin')
@section('title', 'Categories')

@section('content')

<style>
    :root {
        --primary: #318069;
        --primary-light: rgba(49, 128, 105, 0.1);
        --primary-dark: #276854;
        --primary-soft: rgba(49, 128, 105, 0.05);
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --text-light: #94a3b8;
        --border: #e2e8f0;
        --border-light: #f1f5f9;
        --bg-gray: #f8fafc;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        border: 1px solid var(--border);
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        background: var(--primary-light);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
    }

    .stat-icon i {
        font-size: 1.2rem;
        color: var(--primary);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.2rem;
    }

    .stat-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Header Section */
    .header-section {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        color: var(--primary);
        font-size: 1.5rem;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .btn-create {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid var(--border);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .filter-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        display: block;
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .search-input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .filter-select {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        font-size: 0.85rem;
        background: white;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    /* Table Styles */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--bg-gray);
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--border-light);
    }

    .data-table tbody tr:hover {
        background: var(--bg-gray);
    }

    .data-table tbody tr:last-child {
        border-bottom: none;
    }

    .data-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    /* Category Icon */
    .category-icon {
        width: 36px;
        height: 36px;
        background: var(--primary-light);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-icon i {
        font-size: 1rem;
        color: var(--primary);
    }

    /* Badge Styles */
    .parent-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .no-parent-badge {
        background: #f3f4f6;
        color: #9ca3af;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* ID Badge */
    .id-badge {
        background: #f3f4f6;
        color: #6b7280;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.35rem 1rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        cursor: pointer;
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
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
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
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
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

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-info {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .data-table thead {
            display: none;
        }
        
        .data-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1rem;
        }
        
        .data-table tbody td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px dashed var(--border-light);
        }
        
        .data-table tbody td:last-child {
            border-bottom: none;
        }
        
        .data-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary);
            margin-right: 1rem;
            font-size: 0.75rem;
        }
        
        .pagination-wrapper {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="pb-3">
    <!-- Stats Cards -->
    @php
        $totalCategories = $categories->total();
        $totalSubCategories = $categories->whereNotNull('parent_id')->count();
        $topLevelCategories = $categories->whereNull('parent_id')->count();
    @endphp

    <!-- Header Actions -->
    <div class="header-actions">
        <div class="header-section">
            <h1 class="page-title">
                <i class="ri-folder-2-line"></i>
                Categories
            </h1>
            <p class="page-subtitle">Manage article categories and hierarchy for your blog</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn-create">
            <i class="ri-add-line"></i>
            Add Category
        </a>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-5 col-lg-4">
                <label class="filter-label">Search</label>
                <div class="search-wrapper">
                    <i class="ri-search-line"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="search-input"
                           placeholder="Search by name...">
                </div>
            </div>

            <div class="col-12 col-md-5 col-lg-4">
                <label class="filter-label">Parent Category</label>
                <select name="parent_id" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}"
                            {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-filter-3-line me-1"></i> Filter
                    </button>
                    <a href="{{ route('categories.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 70px"># ID</th>
                            <th>Category Name</th>
                            <th style="width: 200px">Parent</th>
                            <th style="width: 100px">Articles</th>
                            <th style="width: 180px" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td data-label="# ID">
                                <span class="id-badge">#{{ $cat->id }}</span>
                            </td>

                            <td data-label="Category Name">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="category-icon">
                                        <i class="ri-folder-2-line"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-gray-800">{{ $cat->name }}</div>
                                        <div class="small text-muted">Slug: {{ Str::slug($cat->name) }}</div>
                                    </div>
                                </div>
                            </td>

                            <td data-label="Parent">
                                @if($cat->parent)
                                    <span class="parent-badge">
                                        <i class="ri-folder-2-line"></i>
                                        {{ $cat->parent->name }}
                                    </span>
                                @else
                                    <span class="no-parent-badge">
                                        <i class="ri-subtract-line"></i>
                                        No Parent
                                    </span>
                                @endif
                            </td>

                            <td data-label="Articles" class="text-center">
                                <span >
                                    <b>{{ number_format($cat->posts_count ?? 0) }}</b> 
                                </span>
                            </td>

                            <td data-label="Actions" class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('categories.edit', $cat) }}"
                                       class="action-btn edit text-decoration-none">
                                        <i class="ri-edit-line"></i>
                                    </a>

                                    <form action="{{ route('categories.destroy', $cat) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="action-btn delete border-0"
                                                onclick="return confirm('Are you sure you want to delete "{{ $cat->name }}" category? This action cannot be undone.')">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                    </form>
                                </div>
                              </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="ri-folder-2-line"></i>
                                    </div>
                                    <h5 class="fw-semibold text-gray-800 mb-2">No Categories Found</h5>
                                    <p class="text-muted mb-4">Get started by creating your first category</p>
                                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line me-1"></i> Add Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($categories->total() > 0)
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} entries
            </div>
            <div>
                {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
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