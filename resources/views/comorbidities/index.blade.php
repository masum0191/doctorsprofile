@extends('layouts.admin')
@section('title','Comorbidities')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Comorbidities</h4>
        <p class="text-muted mb-0">Manage patient comorbid conditions</p>
    </div>
    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addComorbidityModal">
        <i class="ri-add-line me-1"></i> Add Comorbidity
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
               placeholder="Search comorbidity">
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Comorbidity Name</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($comorbidities as $comorbidity)
                <tr>
                    <td class="ps-4 text-muted">{{ $comorbidity->id }}</td>
                    <td class="fw-medium">{{ $comorbidity->comorbidity_name }}</td>
                    <td class="text-end pe-4">

                        <button class="btn btn-sm btn-outline-warning me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editComorbidity{{ $comorbidity->id }}">
                            <i class="ri-edit-line"></i>
                        </button>

                        <form method="POST"
                              action="{{ route('admin.comorbidities.destroy',$comorbidity) }}"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this comorbidity?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>

                    </td>
                </tr>

                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editComorbidity{{ $comorbidity->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST"
                                  action="{{ route('admin.comorbidities.update',$comorbidity) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Comorbidity</h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label class="form-label">Comorbidity Name</label>
                                    <input type="text"
                                           name="comorbidity_name"
                                           value="{{ $comorbidity->comorbidity_name }}"
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
                        <i class="ri-heart-pulse-line fs-3 d-block mb-2"></i>
                        No comorbidities found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addComorbidityModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.comorbidities.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Comorbidity</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Comorbidity Name</label>
                    <input type="text"
                           name="comorbidity_name"
                           class="form-control"
                           placeholder="e.g. Diabetes, Hypertension"
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
