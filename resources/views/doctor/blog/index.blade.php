@extends('layouts.admin')
@section('title','Manage Blog')

@section('content')

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0">Health Blog Management</h4>
            <small class="text-muted">Create, manage & publish health articles</small>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Write New Post
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control"
                       placeholder="Search by title or excerpt">
            </div>

            <div class="col-md-3">
                <select id="statusFilter" class="form-select">
                    <option value="all">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="col-md-3">
                <select id="categoryFilter" class="form-select">
                    <option value="all">All Categories</option>
                    @foreach($posts->pluck('category')->unique()->filter() as $cat)
                        <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>

{{-- LIST TABLE --}}
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Category</th>
                    <th>Views</th>
                    <th>Published</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody id="postsTable">
            @forelse($posts as $p)
                <tr data-title="{{ strtolower($p->title) }}"
                    data-excerpt="{{ strtolower($p->excerpt) }}"
                    data-status="{{ $p->is_published ? 'published' : 'draft' }}"
                    data-category="{{ strtolower($p->category->name ?? '') }}">

                    <td>{{ $p->id }}</td>

                    <td>
                        <strong>{{ $p->title }}</strong>
                        <div class="text-muted small">
                            {{ Str::limit($p->excerpt, 80) }}
                        </div>
                    </td>

                    <td>
                        @if($p->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-warning text-dark">Draft</span>
                        @endif
                    </td>

                    <td>
                        {{ $p->category->name ?? '—' }}
                    </td>

                    <td>{{ number_format($p->view_count) }}</td>

                    <td>
                        {{ optional($p->published_at)->format('d M Y') ?? '—' }}
                    </td>

                    <td class="text-end">
                        <a href="{{ route('admin.posts.edit',$p) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="ri-edit-line"></i>
                        </a>

                        @if($p->is_published)
                            <a href="{{ route('articles.show',$p->slug) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-success">
                                <i class="ri-external-link-line"></i>
                            </a>
                        @endif

                        <button class="btn btn-sm btn-outline-danger"
                                onclick="confirmDelete({{ $p->id }})">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        No blog posts found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
@if($posts->hasPages())
<div class="d-flex justify-content-between align-items-center mt-3">
    <small class="text-muted">
        Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }}
    </small>
    {{ $posts->links('pagination::bootstrap-5') }}
</div>
@endif

{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0">This post will be permanently deleted.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterTable() {
    const q = searchInput.value.toLowerCase();
    const status = statusFilter.value;
    const cat = categoryFilter.value;

    document.querySelectorAll('#postsTable tr').forEach(row => {
        const match =
            (row.dataset.title.includes(q) || row.dataset.excerpt.includes(q)) &&
            (status === 'all' || row.dataset.status === status) &&
            (cat === 'all' || row.dataset.category === cat);

        row.style.display = match ? '' : 'none';
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
    form.action = `/admin/posts/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const categoryFilter = document.getElementById('categoryFilter');

searchInput.addEventListener('input', filterTable);
statusFilter.addEventListener('change', filterTable);
categoryFilter.addEventListener('change', filterTable);
</script>
@endpush
