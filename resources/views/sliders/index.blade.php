@extends('layouts.admin')
@section('title','Slider Management')

@section('content')

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Title / Subtitle">
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="1" @selected(request('status')==='1')>Active</option>
                    <option value="0" @selected(request('status')==='0')>Inactive</option>
                </select>
            </div>

            <div class="col-md-5 text-end">
                <button class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-light">Reset</a>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-success">Add Slider</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sliders as $slider)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($slider->image)
                            <img src="{{ url($slider->image) }}" height="45" class="rounded">
                        @endif
                    </td>
                    <td>
                        <strong>{{ $slider->title }}</strong><br>
                        <small class="text-muted">{{ $slider->sub_title }}</small>
                    </td>
                    <td>{{ $slider->order }}</td>
                    <td>
                        <span class="badge {{ $slider->status ? 'bg-success':'bg-secondary' }}">
                            {{ $slider->status ? 'Active':'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.sliders.edit',$slider) }}" class="btn btn-sm btn-warning">
                                                        <i class="ri-edit-line"></i>

                        </a>
                        <form action="{{ route('admin.sliders.destroy',$slider) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete slider?')" class="btn btn-sm btn-danger">                                <i class="ri-delete-bin-6-line"></i>
</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No sliders found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $sliders->links('pagination::bootstrap-5') }}
</div>

@endsection
