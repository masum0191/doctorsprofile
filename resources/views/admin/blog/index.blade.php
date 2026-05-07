@extends('layouts.supperadmin')
@section('title', 'Manage Blog')

@section('content')
<style>
    :root {
        --primary: #318069;
        --primary-light: #e8f5f0;
        --primary-dark: #276854;
        --primary-soft: #f0fdf4;
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
        grid-template-columns: repeat(4, 1fr);
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

    /* Header Card */
    .header-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.25rem 0;
    }

    .header-title p {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
    }

    .btn-create {
        background: white;
        color: var(--primary);
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.85rem;
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

    .filter-input {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .filter-input:focus {
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
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background: var(--bg-gray);
    }

    .data-table tbody td {
        padding: 1rem;
        font-size: 0.85rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    /* Post Title Cell */
    .post-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .post-excerpt {
        font-size: 0.7rem;
        color: var(--text-light);
        line-height: 1.4;
    }

    /* Badges */
    .badge-status {
        padding: 0.25rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-published {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-draft {
        background: #fef3c7;
        color: #92400e;
    }

    .category-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    /* View Count */
    .view-count {
        font-weight: 600;
        color: var(--text-dark);
    }

    .view-label {
        font-size: 0.65rem;
        color: var(--text-light);
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .action-edit {
        background: var(--primary-light);
        color: var(--primary);
    }

    .action-edit:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .action-view {
        background: #dbeafe;
        color: #2563eb;
    }

    .action-view:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-2px);
    }

    .action-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .header-card {
            flex-direction: column;
            text-align: center;
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
            justify-content: space-between;
            align-items: center;
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
            color: var(--text-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
        }
        
        .action-btns {
            justify-content: flex-start;
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
        $totalPosts = $posts->total();
        $publishedPosts = $posts->where('is_published', 1)->count();
        $draftPosts = $posts->where('is_published', 0)->count();
        $totalViews = $posts->sum('view_count');
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalPosts) }}</div>
            <div class="stat-label">Total Articles</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($publishedPosts) }}</div>
            <div class="stat-label">Published</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($draftPosts) }}</div>
            <div class="stat-label">Drafts</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalViews) }}</div>
            <div class="stat-label">Total Views</div>
        </div>
    </div>

    <!-- Header Card -->
    <div class="header-card">
        <div class="header-title">
            <h2><i class="ri-article-line me-2"></i>Health Blog Management</h2>
            <p>Create, manage & publish health articles for your audience</p>
        </div>
        <a href="{{ route('posts.create') }}" class="btn-create">
            <i class="ri-add-line me-1"></i> Write New Post
        </a>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="filter-label">Search</label>
                <input type="text" id="searchInput" class="filter-input" placeholder="Search by title or excerpt...">
            </div>
            <div class="col-md-3">
                <label class="filter-label">Status</label>
                <select id="statusFilter" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="filter-label">Category</label>
                <select id="categoryFilter" class="filter-select">
                    <option value="all">All Categories</option>
                    @foreach($posts->pluck('category')->unique()->filter() as $cat)
                        <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="ri-refresh-line me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px">#ID</th>
                            <th>Title</th>
                            <th style="width: 100px">Status</th>
                            <th style="width: 120px">Category</th>
                            <th style="width: 80px">Views</th>
                            <th style="width: 100px">Published</th>
                            <th style="width: 120px" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="postsTable">
                    @forelse($posts as $p)
                        <tr data-title="{{ strtolower($p->title) }}"
                            data-excerpt="{{ strtolower(strip_tags($p->excerpt)) }}"
                            data-status="{{ $p->is_published ? 'published' : 'draft' }}"
                            data-category="{{ strtolower($p->category ?? '') }}">

                            <td data-label="#ID">
                                <span class="text-muted">#{{ $p->id }}</span>
                            </td>

                            <td data-label="Title">
                                <div class="post-title">{{ $p->title }}</div>
                                <div class="post-excerpt">{{ Str::limit(strip_tags($p->excerpt), 40) }}</div>
                            </td>

                            <td data-label="Status">
                                @if($p->is_published)
                                    <span class="badge-status badge-published">
                                        <i class="ri-checkbox-circle-line"></i> Published
                                    </span>
                                @else
                                    <span class="badge-status badge-draft">
                                        <i class="ri-draft-line"></i> Draft
                                    </span>
                                @endif
                            </td>

                            <td data-label="Category">
                                @if($p->category)
                                    <span class="category-badge">
                                        <i class="ri-folder-2-line me-1"></i>{{ $p->category->name ?? $p->category }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td data-label="Views">
                                <div class="view-count">{{ number_format($p->view_count) }}</div>
                                <div class="view-label">views</div>
                            </td>

                           <td data-label="Published" style="font-size: 12px; color: var(--text-muted);">
                                {{ optional($p->published_at)->format('d M Y') ?? '—' }}
                            </td>

                            <td data-label="Actions" class="text-end">
                                <div class="action-btns">
                                    <a href="{{ route('posts.edit', $p) }}" class="action-btn action-edit" title="Edit Post">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    @if($p->is_published)
                                        <a href="{{ url('singles-article', $p->slug) }}" target="_blank" class="action-btn action-view" title="View Post">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    @endif
                                    <button class="action-btn action-delete" onclick="confirmDelete({{ $p->id }})" title="Delete Post">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="ri-article-line"></i>
                                    </div>
                                    <h5 class="fw-semibold text-gray-800 mb-2">No Blog Posts</h5>
                                    <p class="text-muted mb-3">Get started by creating your first article</p>
                                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line me-1"></i> Write New Post
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
    @if($posts->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }} articles
            </div>
            <div>
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold text-danger">
                        <i class="ri-delete-bin-line me-2"></i>Confirm Delete
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <div class="empty-icon" style="width: 60px; height: 60px; margin: 0 auto 1rem;">
                            <i class="ri-alert-line fs-1 text-danger"></i>
                        </div>
                    </div>
                    <p class="mb-0">This post will be <strong>permanently deleted</strong>.</p>
                    <p class="text-muted small mt-1">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-1"></i>Delete Permanently
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value;
    const categoryValue = categoryFilter.value;

    document.querySelectorAll('#postsTable tr').forEach(row => {
        const title = row.dataset.title || '';
        const excerpt = row.dataset.excerpt || '';
        const status = row.dataset.status || '';
        const category = row.dataset.category || '';

        const matchesSearch = title.includes(searchTerm) || excerpt.includes(searchTerm);
        const matchesStatus = statusValue === 'all' || status === statusValue;
        const matchesCategory = categoryValue === 'all' || category === categoryValue;

        row.style.display = (matchesSearch && matchesStatus && matchesCategory) ? '' : 'none';
    });
}

function resetFilters() {
    searchInput.value = '';
    statusFilter.value = 'all';
    categoryFilter.value = 'all';
    filterTable();
}

function confirmDelete(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/posts/${id}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const categoryFilter = document.getElementById('categoryFilter');

if (searchInput) searchInput.addEventListener('input', filterTable);
if (statusFilter) statusFilter.addEventListener('change', filterTable);
if (categoryFilter) categoryFilter.addEventListener('change', filterTable);
</script>
@endpush