@extends('layouts.admin')
@section('title','Post Types')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Post Types</h4>
        <p class="text-muted mb-0">Manage article & content types</p>
    </div>
    <a href="{{ route('admin.post-types.create') }}" class="btn btn-primary">
        <i class="ri-add-line me-1"></i> New Post Type
    </a>
</div>

{{-- Search --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
                <i class="ri-search-line"></i>
            </span>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control border-start-0"
                   placeholder="Search post type">
        </div>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Name</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($postTypes as $postType)
                <tr>
                    <td class="ps-4 text-muted">{{ $postType->id }}</td>
                    <td>
                        <span class="fw-medium">{{ $postType->name }}</span>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.post-types.edit',$postType) }}"
                           class="btn btn-sm btn-outline-warning me-1">
                            <i class="ri-edit-line"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.post-types.destroy',$postType) }}"
                              class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this post type?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-5 text-muted">
                        <i class="ri-file-list-3-line fs-3 d-block mb-2"></i>
                        No post types found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $postTypes->links('pagination::bootstrap-5') }}
</div>

@endsection
