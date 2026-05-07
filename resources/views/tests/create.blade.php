@extends('layouts.admin')
@section('title','Create Test')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Add New Test</h5>

                <form method="POST" action="{{ route('admin.tests.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Test Name</label>
                        <input type="text"
                               name="test_name"
                               class="form-control"
                               placeholder="Example: CBC, X-Ray Chest, MRI Brain"
                               required>
                        <small class="text-muted">
                            This name will appear in prescriptions
                        </small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.tests.index') }}"
                           class="btn btn-light">Cancel</a>
                        <button class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
