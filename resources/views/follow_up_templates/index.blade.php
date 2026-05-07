@extends('layouts.admin')
@section('title','Follow-Up Templates')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Follow-Up Templates</h4>
        <p class="text-muted mb-0">Reusable follow-up instructions & reminders</p>
    </div>
    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addFollowUpModal">
        <i class="ri-add-line me-1"></i> Add Template
    </button>
</div>

{{-- SEARCH (optional future ready) --}}
<form method="GET" class="mb-3">
    <div class="input-group w-25">
        <span class="input-group-text bg-light">
            <i class="ri-search-line"></i>
        </span>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Search follow-up">
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>

            @forelse($templates as $template)
                <tr>
                    <td class="ps-4 text-muted">{{ $template->id }}</td>
                    <td class="fw-medium">{{ $template->name }}</td>
                    <td class="text-muted">
                        {{ Str::limit($template->details, 80) ?? '—' }}
                    </td>
                    <td class="text-end pe-4">

                        <button class="btn btn-sm btn-outline-warning me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editTemplate{{ $template->id }}">
                            <i class="ri-edit-line"></i>
                        </button>

                        <form method="POST"
                              action="{{ route('admin.follow-up-templates.destroy',$template) }}"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this follow-up template?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>

                    </td>
                </tr>

                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editTemplate{{ $template->id }}">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">

                            <form method="POST"
                                  action="{{ route('admin.follow-up-templates.update',$template) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Follow-Up Template</h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">Template Name</label>
                                        <input type="text"
                                               name="name"
                                               value="{{ $template->name }}"
                                               class="form-control"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Details</label>
                                        <textarea name="details"
                                                  rows="4"
                                                  class="form-control"
                                                  placeholder="Follow-up instructions, advice, next visit notes">{{ $template->details }}</textarea>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-light"
                                            data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Update
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="ri-calendar-check-line fs-3 d-block mb-2"></i>
                        No follow-up templates found
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addFollowUpModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.follow-up-templates.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Follow-Up Template</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Template Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="e.g. 7-Day Follow-Up"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Details</label>
                        <textarea name="details"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Follow-up instructions, medication review, test reminders"></textarea>
                    </div>

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
