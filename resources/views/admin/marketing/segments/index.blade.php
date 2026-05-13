@extends('layouts.supperadmin')
@section('title','Audience Segments')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between py-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Audience Segments</h1>
            <p class="text-muted mb-0">Group contacts by targeting rules before launching campaigns.</p>
        </div>

        {{-- Create Button --}}
        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#createSegmentModal">
            <i class="ri-add-line me-1"></i>
            New Segment
        </button>
    </div>

    {{-- Error --}}
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    {{-- Segment List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="ri-list-check-2 text-primary me-1"></i>
                Segment List
            </h6>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead class="bg-light small text-uppercase">
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Name</th>
                        <th>Rules (JSON)</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($segments as $segment)
                    <tr>
                        <td>{{ $segment->id }}</td>
                        <td class="fw-semibold">{{ $segment->name }}</td>
                        <td>
                            <code class="small">
                                {{ json_encode($segment->rules) }}
                            </code>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSegmentModal{{ $segment->id }}">
                                <i class="ri-edit-2-line"></i>
                                Edit
                            </button>
                        </td>
                    </tr>

                    {{-- EDIT MODAL --}}
                    <div class="modal fade"
                         id="editSegmentModal{{ $segment->id }}"
                         tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">

                                <form method="POST"
                                      action="{{ route('superadmin.marketing.segments.update', $segment->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Segment</h5>
                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Segment Name
                                            </label>
                                            <input type="text"
                                                   name="name"
                                                   class="form-control"
                                                   value="{{ $segment->name }}"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Rules (JSON)
                                            </label>
                                            <textarea name="rules"
                                                      rows="4"
                                                      class="form-control"
                                                      required>{{ json_encode($segment->rules) }}</textarea>
                                            <small class="text-muted">
                                                Must be valid JSON
                                            </small>
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
                                            Update
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            No segments found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top d-flex justify-content-end">
            {{ $segments->links() }}
        </div>
    </div>
</div>

{{-- CREATE MODAL --}}
<div class="modal fade" id="createSegmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('superadmin.marketing.segments.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Create New Segment</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Segment Name
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="e.g. Senior Doctors"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Rules (JSON)
                        </label>
                        <textarea name="rules"
                                  rows="4"
                                  class="form-control"
                                  placeholder='{"age":">30","specialty":"cardiology"}'
                                  required></textarea>
                        <small class="text-muted">
                            Must be valid JSON
                        </small>
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
                        Create Segment
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
