@extends('layouts.supperadmin')
@section('title','Create Post Type')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Create Post Type</h5>

                <form method="POST" action="{{ route('post-types.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Post Type Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Example: Blog, News, Notice"
                               required>
                        <small class="text-muted">
                            Used to group articles and content
                        </small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('post-types.index') }}"
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
