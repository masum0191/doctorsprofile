@extends('layouts.admin')
@section('title','Categories')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        + Add Category
    </a>
</div>

{{-- FILTER --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Search category">
    </div>

    <div class="col-md-4">
        <select name="parent_id" class="form-select">
            <option value="">All Parents</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}"
                    @selected(request('parent_id')==$parent->id)>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-secondary w-100">Filter</button>
    </div>

    <div class="col-md-2">
        <a href="{{ route('admin.categories.index') }}"
           class="btn btn-light w-100">Reset</a>
    </div>
</form>

{{-- TABLE --}}
<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Parent</th>
            <th width="160">Action</th>
        </tr>
    </thead>
    <tbody>
    @forelse($categories as $cat)
        <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->name }}</td>
            <td>{{ $cat->parent?->name ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.categories.edit',$cat) }}"
                   class="btn btn-sm btn-warning">Edit</a>

                <form action="{{ route('admin.categories.destroy',$cat) }}"
                      method="POST"
                      class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete this category?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center text-muted">No categories found</td>
        </tr>
    @endforelse
    </tbody>
</table>

{{ $categories->withQueryString()->links('pagination::bootstrap-5') }}

@endsection
