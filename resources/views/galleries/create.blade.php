@extends('layouts.admin')
@section('title','Upload Gallery')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Upload Gallery Images</h5>

                <form method="POST"
                      action="{{ route('admin.galleries.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Images</label>
                        <input type="file"
                               name="images[]"
                               class="form-control"
                               multiple
                               required>
                        <small class="text-muted">
                            JPG, PNG, WEBP (Max 2MB each)
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Video URL (optional)</label>
                        <input type="url"
                               name="video_url"
                               class="form-control"
                               placeholder="https://youtube.com/...">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.galleries.index') }}"
                           class="btn btn-light">Cancel</a>
                        <button class="btn btn-primary">
                            <i class="ri-upload-line me-1"></i> Upload
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection
