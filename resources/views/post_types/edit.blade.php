@extends('layouts.admin')
@section('title','Edit Post Type')

@section('content')

<div class="card">
    <div class="card-header fw-bold">Edit Post Type</div>
    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.post-types.update',$postType) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Post Type Name</label>
                <input type="text"
                       name="name"
                       value="{{ $postType->name }}"
                       class="form-control"
                       required>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.post-types.index') }}"
               class="btn btn-secondary">Back</a>
        </form>

    </div>
</div>

@endsection
