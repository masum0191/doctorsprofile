@extends('layouts.admin')
@section('title','Plan Templates')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Plan Templates</h4>
        <p class="text-muted mb-0">Reusable treatment & care plan templates</p>
    </div>
    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addPlanModal">
        <i class="ri-add-line me-1"></i> Add Plan
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
               placeholder="Search plan">
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Plan Name</th>
                    <th>Details</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($plans as $plan)
                <tr>
                    <td class="ps-4 text-muted">{{ $plan->id }}</td>
                    <td class="fw-medium">{{ $plan->plan_name }}</td>
                    <td class="text-muted">
                        {{ Str::limit($plan->plan_details, 80) ?? '—' }}
                    </td>
                    <td class="text-end pe-4">

                        <button class="btn btn-sm btn-outline-warning me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editPlan{{ $plan->id }}">
                            <i class="ri-edit-line"></i>
                        </button>

                        <form method="POST"
                              action="{{ route('admin.plan-templates.destroy',$plan) }}"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this plan template?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>

                    </td>
                </tr>

                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editPlan{{ $plan->id }}">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form method="POST"
                                  action="{{ route('admin.plan-templates.update',$plan) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Plan Template</h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Plan Name</label>
                                        <input type="text"
                                               name="plan_name"
                                               value="{{ $plan->plan_name }}"
                                               class="form-control"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Plan Details</label>
                                        <textarea name="plan_details"
                                                  rows="4"
                                                  class="form-control"
                                                  placeholder="Write plan instructions or notes">{{ $plan->plan_details }}</textarea>
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
                        <i class="ri-file-list-3-line fs-3 d-block mb-2"></i>
                        No plan templates found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addPlanModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.plan-templates.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Plan Template</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text"
                               name="plan_name"
                               class="form-control"
                               placeholder="e.g. Diabetes Care Plan"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Plan Details</label>
                        <textarea name="plan_details"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Detailed instructions, lifestyle advice, follow-ups"></textarea>
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
