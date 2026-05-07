@extends('layouts.admin')
@section('title','Create Event')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Create Event</h5>

                <form method="POST"
                      action="{{ route('admin.events.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sub Title</label>
                            <input type="text" name="sub_title" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Images</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Video URL</label>
                            <input type="url" name="video_url" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Venue</label>
                            <input type="text" name="venue" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Publish Date</label>
                            <input type="date" name="publish_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control"></textarea>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save Event
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection
