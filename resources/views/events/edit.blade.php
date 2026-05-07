@extends('layouts.admin')
@section('title', 'Edit Event')
<style>

.gallery-thumb {
    width: 120px;
}
.gallery-thumb img {
    height: 90px;
    width: 100%;
    object-fit: cover;
    cursor: pointer;
}
.gallery-thumb button {
    border-radius: 50%;
}

</style>

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-semibold mb-0">Edit Event</h5>
                            <small class="text-muted">Update event details & gallery</small>
                        </div>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-light">
                            ← Back
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- TITLE --}}
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" value="{{ old('title', $event->title) }}"
                                    class="form-control" required>
                            </div>

                            {{-- SUB TITLE --}}
                            <div class="col-md-6">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" value="{{ old('sub_title', $event->sub_title) }}"
                                    class="form-control">
                            </div>

                            {{-- VENUE --}}
                            <div class="col-md-6">
                                <label class="form-label">Venue</label>
                                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
                                    class="form-control">
                            </div>

                            {{-- VIDEO --}}
                            <div class="col-md-6">
                                <label class="form-label">Video URL</label>
                                <input type="url" name="video_url" value="{{ old('video_url', $event->video_url) }}"
                                    class="form-control">
                            </div>

                            {{-- DATE --}}
                            <div class="col-md-6">
                                <label class="form-label">Publish Date</label>
                                <input type="date" name="publish_date"
                                    value="{{ old('publish_date', $event->publish_date?->format('Y-m-d')) }}"
                                    class="form-control" required>
                            </div>

                            {{-- STATUS --}}
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" @selected($event->status)>Active</option>
                                    <option value="0" @selected(!$event->status)>Inactive</option>
                                </select>
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="4" class="form-control">{{ old('description', $event->description) }}</textarea>
                            </div>

                            {{-- EXISTING GALLERY --}}
                            @if ($event->image_gallery)
                                <div class="d-flex flex-wrap gap-3">

                                    @foreach ($event->image_gallery as $img)
                                        <div class="position-relative gallery-thumb">

                                            <img src="{{ url($img) }}" class="img-thumbnail gallery-image"
                                                data-bs-toggle="modal" data-bs-target="#imagePreviewModal"
                                                data-image="{{ url($img) }}">

                                            {{-- DELETE BUTTON --}}
                                            <button type="button"
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-image-btn"
                                                data-image="{{ $img }}"
                                                data-url="{{ route('admin.events.gallery-image.delete', $event) }}">
                                                <i class="ri-close-line"></i>
                                            </button>

                                        </div>
                                    @endforeach

                                </div>
                            @endif


                            {{-- ADD MORE IMAGES --}}
                            <div class="col-md-12">
                                <label class="form-label">Add More Images</label>
                                <input type="file" name="images[]" class="form-control" multiple>
                                <small class="text-muted">
                                    Uploading images will be added to existing gallery
                                </small>
                            </div>

                        </div>

                        {{-- ACTION --}}
                        <div class="mt-4 text-end">
                            <button class="btn btn-primary px-4">
                                <i class="ri-save-line me-1"></i> Update Event
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="deleteImageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Delete Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this image?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteImage">Delete</button>
            </div>

        </div>
    </div>
</div>

    @push('scripts')
       <script>
document.addEventListener('DOMContentLoaded', function () {

    let deleteImage = null;
    let deleteUrl = null;
    let deleteButton = null;

    document.querySelectorAll('.delete-image-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();

            deleteImage = this.dataset.image;
            deleteUrl = this.dataset.url;
            deleteButton = this.closest('.gallery-thumb');

            new bootstrap.Modal(document.getElementById('deleteImageModal')).show();
        });
    });

    document.getElementById('confirmDeleteImage').addEventListener('click', function () {

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ image: deleteImage })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                deleteButton.remove();
                bootstrap.Modal.getInstance(
                    document.getElementById('deleteImageModal')
                ).hide();
            }
        });

    });

});
</script>

    @endpush
@endsection
