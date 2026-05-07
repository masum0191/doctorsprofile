@extends('layouts.admin')
@section('title','Investigations')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Investigations</h4>
        <p class="text-muted mb-0">Manage diagnostic & lab investigations</p>
    </div>
    <a href="{{ route('admin.investigations.create') }}" class="btn btn-primary">
        <i class="ri-add-line me-1"></i> New Investigation
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
                   placeholder="Search investigation">
        </div>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Investigation Name</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($investigations as $inv)
                <tr>
                    <td class="ps-4 text-muted">{{ $inv->id }}</td>
                    <td class="fw-medium">{{ $inv->investigation_name }}</td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.investigations.edit',$inv) }}"
                           class="btn btn-sm btn-outline-warning me-1">
                            <i class="ri-edit-line"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.investigations.destroy',$inv) }}"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this investigation?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-5 text-muted">
                        <i class="ri-microscope-line fs-3 d-block mb-2"></i>
                        No investigations found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $investigations->withQueryString()->links('pagination::bootstrap-5') }}
</div>

@endsection
