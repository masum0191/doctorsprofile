@extends('layouts.admin')
@section('title', 'Page List')
@section('content')
<style>
    .card {
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        border-radius: 0.5rem;
        overflow: hidden;
        top: 8px;
    }

    .card-header {
        background: linear-gradient(135deg, #007bff 0%, #224abe 100%);
        color: white;
        padding: .8rem 1.5rem;
        border-bottom: none;
    }

    .card-header h5 {
        font-weight: 600;
        margin-bottom: 0;
    }

    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table thead th {
        background-color: #f8f9fc;
        color: #007bff;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-top: 1px solid #e3e6f0;
    }

    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.03);
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35em 0.65em;
        border-radius: 50rem;
        display: inline-block;
    }

    .badge-yes {
        background-color: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
    }

    .badge-no {
        background-color: rgba(231, 74, 59, 0.1);
        color: #e74a3b;
    }

    .action-btns {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }

    .pagination .page-link {
        color: #007bff;
        font-size: 0.85rem;
        border-radius: 0.25rem;
        margin: 0 0.15rem;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #d1d3e2;
    }

    @media (max-width: 768px) {
        .action-btns {
            flex-wrap: wrap;
        }
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h5 class="mb-3 mb-md-0">
                 Page List
            </h5>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-light margin-end">
                <i class="fas fa-plus me-2"></i> Add New Page
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($pages->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-file-alt fa-2x"></i>
                    <h5 class="mt-2">No pages found</h5>
                    <p class="text-muted">Create your first page to get started</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Show in Menu</th>
                                <th>Published</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $page->title }}</td>
                                <td>{{ $page->slug }}</td>
                                <td>
                                    <span class="status-badge {{ $page->show_in_menu ? 'badge-yes' : 'badge-no' }}">
                                        <i class="fas {{ $page->show_in_menu ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                        {{ $page->show_in_menu ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $page->is_published ? 'badge-yes' : 'badge-no' }}">
                                        <i class="fas {{ $page->is_published ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                        {{ $page->is_published ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end action-btns">
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" 
                                           class="btn btn-primary action-btn" title="Edit">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger action-btn" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.pages.toggle-publish', $page->id) }}" 
                                           class="btn {{ $page->is_published ? 'btn-warning' : 'btn-success' }} action-btn" 
                                           title="{{ $page->is_published ? 'Unpublish' : 'Publish' }}">
                                            <i class="fas {{ $page->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection