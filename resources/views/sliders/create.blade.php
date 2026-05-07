@extends('layouts.admin')
@section('title','Slider Form')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data"
              action="{{ isset($slider) ? route('admin.sliders.update',$slider) : route('admin.sliders.store') }}">
            @csrf
            @isset($slider) @method('PUT') @endisset

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input name="title" class="form-control" value="{{ old('title',$slider->title ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sub Title</label>
                    <input name="sub_title" class="form-control" value="{{ old('sub_title',$slider->sub_title ?? '') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description',$slider->description ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                    @isset($slider->image)
                        <img src="{{ asset('storage/'.$slider->image) }}" class="mt-2 rounded" height="60">
                    @endisset
                </div>

                <div class="col-md-6">
                    <label class="form-label">Video URL</label>
                    <input name="video_url" class="form-control" value="{{ old('video_url',$slider->video_url ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Click URL</label>
                    <input name="click_url" class="form-control" value="{{ old('click_url',$slider->click_url ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Button Text</label>
                    <input name="button_text" class="form-control" value="{{ old('button_text',$slider->button_text ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Target</label>
                    <select name="target" class="form-select">
                        <option value="_self" @selected(old('target',$slider->target ?? '_self')=='_self')>Same Tab</option>
                        <option value="_blank" @selected(old('target',$slider->target ?? '')=='_blank')>New Tab</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Order</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order',$slider->order ?? 0) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" @selected(old('status',$slider->status ?? 1)==1)>Active</option>
                        <option value="0" @selected(old('status',$slider->status ?? 1)==0)>Inactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Start At</label>
                    <input type="datetime-local" name="start_at" class="form-control"
                        value="{{ old('start_at', isset($slider->start_at) ? $slider->start_at->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">End At</label>
                    <input type="datetime-local" name="end_at" class="form-control"
                        value="{{ old('end_at', isset($slider->end_at) ? $slider->end_at->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-success">Save Slider</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

@endsection
