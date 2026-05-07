@extends('layouts.supperadmin')
@section('title','Create Category')

@section('content')

<div class="card">
    <div class="card-header fw-bold">Add New Category</div>
    <div class="card-body">

        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" name="name"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Parent Category</label>
                <select name="parent_id" class="form-select">
                    <option value="">No Parent</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Save</button>
            <a href="{{ route('categories.index') }}"
               class="btn btn-secondary">Back</a>
        </form>

    </div>
</div>

@endsection
