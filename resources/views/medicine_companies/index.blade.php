@extends('layouts.admin')
@section('title','Medicine Companies')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Medicine Companies</h4>
        <p class="text-muted mb-0">Manage pharmaceutical manufacturers</p>
    </div>
    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addCompanyModal">
        <i class="ri-add-line me-1"></i> Add Company
    </button>
</div>

{{-- SEARCH --}}
<form method="GET" class="mb-3">
    <div class="input-group w-25">
        <span class="input-group-text bg-light">
            <i class="ri-search-line"></i>
        </span>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Search company">
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Company Name</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($companies as $company)
                <tr>
                    <td class="ps-4 text-muted">{{ $company->id }}</td>
                    <td class="fw-medium">{{ $company->company_name }}</td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-warning me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editCompany{{ $company->id }}">
                            <i class="ri-edit-line"></i>
                        </button>

                        <form method="POST"
                              action="{{ route('admin.medicine-companies.destroy',$company) }}"
                              class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this company?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editCompany{{ $company->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST"
                                  action="{{ route('admin.medicine-companies.update',$company) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Company</h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label class="form-label">Company Name</label>
                                    <input type="text"
                                           name="company_name"
                                           value="{{ $company->company_name }}"
                                           class="form-control"
                                           required>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-light"
                                            data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="3" class="text-center py-5 text-muted">
                        <i class="ri-hospital-line fs-3 d-block mb-2"></i>
                        No medicine companies found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $companies->links('pagination::bootstrap-5') }}
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addCompanyModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.medicine-companies.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Medicine Company</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Company Name</label>
                    <input type="text"
                           name="company_name"
                           class="form-control"
                           placeholder="e.g. Square Pharmaceuticals"
                           required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
