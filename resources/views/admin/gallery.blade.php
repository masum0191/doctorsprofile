@extends('layouts.forntend')
@section('title', 'Gallery')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center">গ্যালারী</h2>

    <div class="row">
        @forelse($items as $item)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    @if($item->type === 'image')
                        <img src="{{ asset('storage/' . $item->file_path) }}" class="card-img-top" alt="{{ $item->title }}">
                    @elseif($item->type === 'video')
                        @if($item->video_url)
                            {{-- YouTube embed --}}
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ str_replace('watch?v=', 'embed/', $item->video_url) }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @elseif($item->file_path)
                            {{-- Uploaded video --}}
                            <video width="100%" controls>
                                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                                আপনার ব্রাউজার ভিডিও চালাতে পারছে না।
                            </video>
                        @endif
                    @endif
                    <div class="card-body">
                        <h5 class="card-title text-center">{{ $item->title }}</h5>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">কোনো গ্যালারী আইটেম পাওয়া যায়নি।</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
