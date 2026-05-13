@extends('layouts.supperadmin')
@section('title','Message Templates')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between py-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Message Templates</h1>
            <p class="text-muted mb-0">Create reusable email and WhatsApp content for campaigns.</p>
        </div>

        {{-- Create Button --}}
        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#createTemplateModal">
            <i class="ri-add-line me-1"></i>
            New Template
        </button>
    </div>

    {{-- Template List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-file-text-line text-primary me-1"></i>
                Template List
            </h6>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead class="bg-light small text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>Channel</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>{{ $template->id }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ ucfirst($template->channel) }}
                            </span>
                        </td>
                        <td class="fw-semibold">{{ $template->name }}</td>
                        <td>{{ $template->subject ?? '—' }}</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTemplateModal{{ $template->id }}">
                                <i class="ri-edit-2-line"></i>
                                Edit
                            </button>

                            <form method="POST"
                                  action="{{ route('superadmin.marketing.templates.destroy',$template->id) }}"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this template?')">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- EDIT MODAL --}}
                    <div class="modal fade"
                         id="editTemplateModal{{ $template->id }}"
                         tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">

                                <form method="POST"
                                      action="{{ route('superadmin.marketing.templates.update',$template->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Template</h5>
                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <input type="hidden"
                                               name="channel"
                                               value="{{ $template->channel }}">

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    Template Name
                                                </label>
                                                <input type="text"
                                                       name="name"
                                                       class="form-control"
                                                       value="{{ $template->name }}"
                                                       required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    Subject (Email only)
                                                </label>
                                                <input type="text"
                                                       name="subject"
                                                       class="form-control"
                                                       value="{{ $template->subject }}">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">
                                                    Body
                                                </label>
                                                <textarea name="body"
                                                          rows="5"
                                                          class="form-control"
                                                          required>{{ $template->body }}</textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    Variables (JSON)
                                                </label>
                                                <textarea name="variables"
                                                          rows="2"
                                                          class="form-control">{{ json_encode($template->variables) }}</textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    Meta (JSON)
                                                </label>
                                                <textarea name="meta"
                                                          rows="2"
                                                          class="form-control">{{ json_encode($template->meta) }}</textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button class="btn btn-warning">
                                            <i class="ri-save-3-line me-1"></i>
                                            Update Template
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No templates found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top d-flex justify-content-end">
            {{ $templates->links() }}
        </div>
    </div>
</div>

{{-- CREATE MODAL --}}
<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('superadmin.marketing.templates.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Create New Template</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Channel</label>
                            <select name="channel" class="form-select" required>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Template Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Subject (Email only)
                            </label>
                            <input type="text"
                                   name="subject"
                                   class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Body</label>
                            <textarea name="body"
                                      rows="5"
                                      class="form-control"
                                      required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Variables (JSON)
                            </label>
                            <textarea name="variables"
                                      rows="2"
                                      class="form-control"
                                      placeholder='["name","email"]'></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Meta (JSON)
                            </label>
                            <textarea name="meta"
                                      rows="2"
                                      class="form-control"
                                      placeholder='{"category":"promo"}'></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>
                        Create Template
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
