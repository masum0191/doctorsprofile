@extends('layouts.admin')
@section('title','Edit Test')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Edit Test</h5>

                <form method="POST"
                      action="{{ route('admin.tests.update',$test) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Test Name</label>
                        <input type="text"
                               name="test_name"
                               value="{{ $test->test_name }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.tests.index') }}"
                           class="btn btn-light">Cancel</a>
                        <button class="btn btn-primary">
                            <i class="ri-refresh-line me-1"></i> Update
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
