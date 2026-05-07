@extends('layouts.admin')
@section('title','Edit Gallery')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Edit Gallery Item</h5>

                <form method="POST"
                      action="{{ route('admin.galleries.update',$gallery) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Current Image</label><br>
                        <img src="{{ url($gallery->image) }}"
                             class="rounded mb-2"
                             width="150">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Change Image</label>
                        <input type="file"
                               name="image"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Video URL</label>
                        <input type="url"
                               name="video_url"
                               value="{{ $gallery->video_url }}"
                               class="form-control">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.galleries.index') }}"
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
