@extends('layouts.sass')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->excerpt), 160))
@section('meta_keywords', is_array($post->meta_keywords) ? implode(',', $post->meta_keywords) : $post->meta_keywords)
@section('canonical', url('singles-article/' . $post->slug))
@section('ogtype', 'article')

@php
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->meta_title ?? $post->title,
        'description' => $post->meta_description ?? Str::limit(strip_tags($post->excerpt), 160),
        'datePublished' => optional($post->published_at)->toAtomString(),
        'dateModified' => optional($post->updated_at)->toAtomString(),
        'mainEntityOfPage' => url('singles-article/' . $post->slug),
    ];
@endphp

@section('structured_data')
    <script type="application/ld+json">
        @json($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    </script>
@endsection

@section('content')
@php
  $shareUrl = urlencode(request()->fullUrl());
  $shareText = urlencode($post->title);
  $tags = collect($post->tags ?? [])->filter();
  $articleSummary = $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 210);
@endphp

<div class="relative overflow-hidden bg-gradient-to-br from-emerald-50/40 via-white to-teal-50/30 pt-24 pb-16">
  
  <!-- Animated Background Elements -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-[#318069]/5 to-teal-500/5 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-gradient-to-tr from-amber-500/5 to-[#318069]/5 rounded-full blur-3xl"></div>
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-[#318069]/3 rounded-full blur-2xl"></div>
    <div class="absolute top-20 right-1/4 w-32 h-32 border-2 border-[#318069]/10 rounded-2xl rotate-12"></div>
    <div class="absolute bottom-40 left-1/3 w-24 h-24 border-2 border-amber-500/10 rounded-full"></div>
    <div class="absolute inset-0 bg-[linear-gradient(rgba(49,128,105,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(49,128,105,0.03)_1px,transparent_1px)] bg-[size:64px_64px]"></div>
  </div>

  <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb Navigation -->
    <nav class="mb-8" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center gap-2 text-sm">
        <li>
          <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-gray-600 bg-white/80 rounded-full border border-gray-100 shadow-sm hover:bg-[#318069] hover:text-white transition-all duration-200">
            <i class="ri-home-4-line text-sm"></i>
            <span class="hidden sm:inline">Home</span>
          </a>
        </li>
        <li><i class="ri-arrow-right-s-line text-gray-400 text-sm"></i></li>
        <li>
          <a href="{{ url('all-articles') }}" class="px-3 py-1.5 text-gray-600 hover:text-[#318069] transition-colors">Health Blog</a>
        </li>
        <li><i class="ri-arrow-right-s-line text-gray-400 text-sm"></i></li>
        <li class="max-w-xl truncate font-medium text-[#318069]">{{ $post->title }}</li>
      </ol>
    </nav>

    <!-- Main Content Grid -->
    <section class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px] lg:gap-12 xl:gap-16">
      
      <!-- Left Column - Main Article -->
      <div>
        <!-- Category Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#318069]/10 rounded-full">
          <span class="w-2 h-2 rounded-full bg-[#318069] animate-pulse"></span>
          <span class="text-sm font-medium text-[#318069]">{{ $post->category->name ?? 'Health & Wellness' }}</span>
        </div>

        <!-- Title -->
        <h1 class="mt-5 text-3xl font-bold leading-tight tracking-tight text-gray-900 sm:text-4xl md:text-5xl lg:text-5xl">
          {{ $post->title }}
        </h1>

        <!-- Excerpt -->
        <p class="mt-5 text-base leading-relaxed text-gray-600 md:text-lg">
          {{ $articleSummary }}
        </p>

        <!-- Meta Information -->
        <div class="mt-6 flex flex-wrap items-center gap-3">
          <!-- Author -->
          <div class="flex items-center gap-3 px-4 py-2 bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#318069] to-teal-600 flex items-center justify-center text-white font-bold text-sm">
              {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($doctor->name, 0, 1)) }}
            </div>
            <div>
              <div class="font-semibold text-gray-900 text-sm">Dr. {{ $doctor->name }}</div>
              <div class="text-xs text-gray-500">{{ $doctor->qualification ?? 'Medical Professional' }}</div>
            </div>
          </div>

          <!-- Date -->
          <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-100 shadow-sm">
            <i class="ri-calendar-line text-[#318069] text-sm"></i>
            <span class="text-sm text-gray-600">{{ optional($post->published_at)->format('F d, Y') }}</span>
          </div>

          <!-- Read Time -->
          <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-100 shadow-sm">
            <i class="ri-time-line text-[#318069] text-sm"></i>
            <span class="text-sm text-gray-600">{{ $post->readTime }} min read</span>
          </div>

          <!-- Views -->
          <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-100 shadow-sm">
            <i class="ri-eye-line text-[#318069] text-sm"></i>
            <span class="text-sm text-gray-600">{{ number_format((int) ($post->view_count ?? 0)) }} views</span>
          </div>
        </div>


         <!-- Featured Image -->
    @if($post->cover_image)
      <div class="mt-10">
        <div class="relative overflow-hidden rounded-2xl shadow-xl">
          <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-auto object-cover max-h-[500px]" loading="lazy">
          <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        </div>
      </div>
    @endif

    <!-- Article Content -->
    <div class="mt-10">
      
      <!-- Article Body -->
      <article class="bg-white rounded-2xl border border-gray-100 shadow-lg overflow-hidden">
        <div class="px-6 py-8 sm:px-8 lg:px-10">
          <div class="article-content prose prose-lg prose-emerald max-w-none">
            {!! $post->body !!}
          </div>

          <!-- Tags -->
          @if($tags->isNotEmpty())
            <div class="mt-10 pt-6 border-t border-gray-100">
              <div class="flex items-center gap-2 mb-4">
                <i class="ri-price-tag-3-line text-[#318069] text-lg"></i>
                <span class="text-sm font-semibold uppercase tracking-wider text-gray-500">Topics Covered</span>
              </div>
              <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                  <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-sm font-medium">
                    <i class="ri-hashtag text-xs"></i> {{ $tag }}
                  </span>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </article>
    </div>
      </div>

      <!-- Right Column - Sticky Sidebar -->
      <aside class="lg:sticky lg:top-28 space-y-6">
        
        <!-- Article Snapshot Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-[#318069] to-teal-700 px-5 py-5">
            <div class="flex items-center gap-2">
              <i class="ri-file-copy-line text-white/80 text-lg"></i>
              <span class="text-xs font-semibold uppercase tracking-wider text-white/80">Article Snapshot</span>
            </div>
            <h3 class="mt-2 text-xl font-bold text-white">Quick Info</h3>
            <p class="mt-1 text-sm text-white/80">Share, bookmark, and explore more from this doctor.</p>
          </div>

          <div class="p-5 space-y-4">
            <!-- Specialization -->
            <div class="bg-emerald-50 rounded-xl p-4">
              <div class="flex items-center gap-2 mb-2">
                <i class="ri-stethoscope-line text-[#318069] text-sm"></i>
                <span class="text-xs font-semibold uppercase tracking-wider text-[#318069]">Specialization</span>
              </div>
              <p class="text-base font-semibold text-gray-900">{{ $doctor->specialization ?? 'General Medicine' }}</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-3">
              <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-xs uppercase tracking-wider text-gray-500">Published</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ optional($post->published_at)->format('M d, Y') }}</p>
              </div>
              <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-xs uppercase tracking-wider text-gray-500">Reading Time</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $post->readTime }} min</p>
              </div>
            </div>

            <!-- Share Section -->
            <div>
              <div class="flex items-center gap-2 mb-3">
                <i class="ri-share-line text-[#318069] text-sm"></i>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Share Article</span>
              </div>
              <div class="flex flex-wrap gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#1877F2] text-white hover:scale-110 transition-transform duration-200">
                  <i class="ri-facebook-fill text-base"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-900 text-white hover:scale-110 transition-transform duration-200">
                  <i class="ri-twitter-x-line text-base"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#0A66C2] text-white hover:scale-110 transition-transform duration-200">
                  <i class="ri-linkedin-fill text-base"></i>
                </a>
                <a href="mailto:?subject={{ $shareText }}&body={{ $shareUrl }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#318069] text-white hover:scale-110 transition-transform duration-200">
                  <i class="ri-mail-send-fill text-base"></i>
                </a>
                <button onclick="copyToClipboard('{{ $shareUrl }}')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-600 hover:bg-[#318069] hover:text-white transition-all duration-200">
                  <i class="ri-link text-base"></i>
                </button>
              </div>
            </div>

            <!-- More Articles CTA -->
            <div class="border border-dashed border-emerald-200 bg-emerald-50/50 rounded-xl p-4">
              <p class="text-sm font-semibold text-gray-900">More from Dr. {{ $doctor->name }}</p>
              <p class="mt-1 text-xs text-gray-600">Browse more health tips, awareness posts, and patient education.</p>
              <a href="{{ url('all-articles') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-[#318069] hover:gap-2 transition-all duration-200">
                Explore Health Blog
                <i class="ri-arrow-right-line text-sm"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Author Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg p-5">
          <div class="flex items-center gap-3 mb-4">
            <i class="ri-user-star-line text-[#318069] text-lg"></i>
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">About The Author</span>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#318069] to-teal-600 flex items-center justify-center text-white font-bold text-xl">
              {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($doctor->name, 0, 1)) }}
            </div>
            <div class="flex-1">
              <h4 class="text-lg font-bold text-gray-900">Dr. {{ $doctor->name }}</h4>
              <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                {{ $doctor->qualification ?? 'Medical Professional' }} specializing in {{ $doctor->specialization ?? 'General Medicine' }}.
              </p>
              <div class="mt-3 flex flex-wrap gap-2">
                @if(!empty($doctor->experience_years))
                  <span class="inline-flex items-center gap-1 text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">
                    <i class="ri-briefcase-line text-xs"></i> {{ $doctor->experience_years }}+ years
                  </span>
                @endif
                <span class="inline-flex items-center gap-1 text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">
                  <i class="ri-stethoscope-line text-xs"></i> {{ $doctor->specialization ?? 'General Practice' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Newsletter CTA -->
        <div class="bg-gradient-to-r from-[#318069] to-teal-700 rounded-2xl p-5 text-white shadow-lg">
          <div class="flex items-center gap-2 mb-3">
            <i class="ri-mail-send-line text-white/80 text-lg"></i>
            <span class="text-xs font-semibold uppercase tracking-wider text-white/80">Stay Updated</span>
          </div>
          <h4 class="text-xl font-bold">Health Tips Weekly</h4>
          <p class="mt-2 text-sm text-white/80">Get the latest medical insights delivered to your inbox.</p>
          <button class="mt-4 w-full bg-white text-[#318069] py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-all duration-200">
            Subscribe Now
          </button>
        </div>
      </aside>
    </section>

   

    <!-- Related Articles Section -->
    @if(isset($related) && $related->count() > 0)
      <section class="mt-16">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <i class="ri-article-line text-[#318069] text-lg"></i>
              <span class="text-xs font-semibold uppercase tracking-wider text-[#318069]">Continue Reading</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Related Health Articles</h2>
            <p class="mt-2 text-gray-600">More practical, readable medical articles from our library.</p>
          </div>
          <a href="{{ url('all-articles') }}" class="inline-flex items-center gap-2 text-[#318069] font-semibold hover:gap-3 transition-all duration-200">
            View All Articles
            <i class="ri-arrow-right-line"></i>
          </a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          @foreach($related as $relatedPost)
            <div class="group bg-white rounded-xl border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
              @if($relatedPost->cover_image)
                <div class="relative overflow-hidden h-48">
                  <img src="{{ $relatedPost->cover_image }}" alt="{{ $relatedPost->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                  <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/40 to-transparent"></div>
                  <span class="absolute top-3 left-3 inline-flex px-2 py-1 bg-white/90 backdrop-blur rounded-md text-xs font-semibold text-[#318069]">
                    {{ $relatedPost->category->name ?? 'Health Blog' }}
                  </span>
                </div>
              @endif

              <div class="p-5">
                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                  <span class="flex items-center gap-1">
                    <i class="ri-calendar-line text-xs"></i>
                    {{ optional($relatedPost->published_at)->format('M d, Y') }}
                  </span>
                  <span class="flex items-center gap-1">
                    <i class="ri-time-line text-xs"></i>
                    {{ $relatedPost->readTime }} min read
                  </span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 line-clamp-2 group-hover:text-[#318069] transition-colors">
                  {{ $relatedPost->title }}
                </h3>

                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                  {{ \Illuminate\Support\Str::limit(strip_tags($relatedPost->excerpt ?: $relatedPost->body), 100) }}
                </p>

                <a href="{{ url('singles-article/' . $relatedPost->slug) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[#318069] hover:gap-2 transition-all duration-200">
                  Read Article
                  <i class="ri-arrow-right-line text-sm"></i>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  </div>
</div>




<!-- Copy to Clipboard Script -->
<script>
  function copyToClipboard(text) {
    navigator.clipboard.writeText(decodeURIComponent(text)).then(function() {
      // Show temporary success message
      const btn = event.currentTarget;
      const originalHtml = btn.innerHTML;
      btn.innerHTML = '<i class="ri-check-line text-base"></i>';
      setTimeout(() => {
        btn.innerHTML = originalHtml;
      }, 2000);
    });
  }
</script>

<style>
  /* Enhanced Article Content Styles */
  .article-content {
    color: #334155;
    line-height: 1.75;
  }

  .article-content > *:first-child {
    margin-top: 0;
  }

  .article-content > *:last-child {
    margin-bottom: 0;
  }

  .article-content p {
    margin-bottom: 1.5rem;
  }

  .article-content h2 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    color: #0f172a;
    letter-spacing: -0.02em;
  }

  .article-content h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 0.875rem;
    color: #1e293b;
    letter-spacing: -0.01em;
  }

  .article-content h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #1e293b;
  }

  .article-content ul,
  .article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
  }

  .article-content li {
    margin-bottom: 0.5rem;
  }

  .article-content blockquote {
    margin: 1.5rem 0;
    padding: 1rem 1.5rem;
    border-left: 4px solid #318069;
    background: #f0fdf4;
    border-radius: 0 1rem 1rem 0;
    font-style: italic;
    color: #166534;
  }

  .article-content a {
    color: #318069;
    text-decoration: underline;
    text-decoration-thickness: 1px;
    text-underline-offset: 2px;
  }

  .article-content a:hover {
    color: #276854;
  }

  .article-content img {
    margin: 1.5rem auto;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    max-width: 100%;
    height: auto;
  }

  .article-content table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
    border-radius: 0.75rem;
    overflow: hidden;
  }

  .article-content th,
  .article-content td {
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
    text-align: left;
  }

  .article-content th {
    background: #f8fafc;
    font-weight: 600;
    color: #0f172a;
  }

  /* Print Styles */
  @media print {
    .lg\:sticky,
    .lg\:top-28,
    aside,
    nav,
    button,
    .subscribe-btn,
    .action-buttons {
      display: none !important;
    }
    
    .article-content {
      font-size: 12pt;
      line-height: 1.5;
    }
    
    .bg-white,
    .rounded-2xl,
    .shadow-lg {
      background: white !important;
      box-shadow: none !important;
      border: none !important;
    }
  }

  /* Line Clamp Utility */
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>
@endsection
