@extends('layouts.admin')
@section('title','Gallery')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Gallery</h4>
        <p class="text-muted mb-0">Manage images & video gallery</p>
    </div>
    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
        <i class="ri-image-add-line me-1"></i> Upload Images
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Image</th>
                    <th>Video URL</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($galleries as $gallery)
                <tr>
                    <td class="ps-4">
@if($gallery->image===null)                        <span class="text-muted">No Image</span>
@else
                        <img src="{{ url( $gallery->image) }}"
                             class="rounded"
                             width="80" height="60"
                             style="object-fit:cover">
@endif
                    </td>
                    <td>
                        @if($gallery->video_url)
                            <a href="{{ $gallery->video_url }}" target="_blank"
                               class="text-decoration-none">
                                {{ Str::limit($gallery->video_url, 40) }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.galleries.edit',$gallery) }}"
                           class="btn btn-sm btn-outline-warning me-1">
                            <i class="ri-edit-line"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.galleries.destroy',$gallery) }}"
                              class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this image?')">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-5 text-muted">
                        <i class="ri-gallery-line fs-3 d-block mb-2"></i>
                        No gallery items found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $galleries->links('pagination::bootstrap-5') }}
</div>

@endsection
