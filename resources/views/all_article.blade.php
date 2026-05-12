@extends('layouts.sass')


@section('title', 'Articles ')
@section('meta_description', 'Read health articles, doctor insights, and patient education resources.')
@section('canonical', route('articles.index'))

@section('content')
    <section id="blog" class="pt-24 pb-12 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-8">
                <div class="inline-block px-4 py-2 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium mb-4">
                    Health Blog</div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Health News & Articles
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Stay informed with expert medical advice,
                    health tips, and the latest insights in healthcare</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($posts as $p)
                    <a href="{{ url('singles-article', $p->slug) }}" class="block group">
                        <article
                            class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer">
                            
                            <div class="relative overflow-hidden">
                                <img alt="{{ $p->title }}"
                                    class="w-full h-56 object-cover object-top group-hover:scale-110 transition-transform duration-500"
                                    src="{{ $p->cover_image ?: 'https://via.placeholder.com/600x400?text=Blog' }}">
                                
                                @if ($p->category)
                                    <div
                                        class="absolute top-4 left-4 bg-cyan-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $p->category->name }}
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                                    <div class="flex items-center gap-1">
                                        <i class="ri-calendar-line"></i>
                                        <span>{{ optional($p->published_at)->format('F d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="ri-time-line"></i>
                                        <span>{{ $p->readTime }} min read</span>
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-cyan-600 transition-colors">
                                    {{ $p->title }}
                                </h3>

                                @if ($p->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $p->excerpt }}</p>
                                @endif

                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center">
                                            <i class="ri-user-line text-white text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $doctor->name }}</span>
                                    </div>

                                    <span class="text-cyan-600 font-semibold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                                        Read More <i class="ri-arrow-right-line"></i>
                                    </span>
                                </div>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
