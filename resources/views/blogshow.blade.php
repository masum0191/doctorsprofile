@extends('layouts.forntend')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->excerpt), 160))
@section('meta_keywords', is_array($post->meta_keywords) ? implode(',', $post->meta_keywords) : $post->meta_keywords)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 py-8">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Enhanced Breadcrumb --}}
    <nav class="mb-8" aria-label="Breadcrumb">
      <ol class="flex items-center space-x-2 text-sm text-slate-600">
        <li>
          <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors duration-200 flex items-center">
            <i class="bi bi-house-door mr-2"></i>
            Home
          </a>
        </li>
        <li><i class="bi bi-chevron-right text-slate-400"></i></li>
        <li>
          <a href="{{ route('articles.index', $doctor->id) }}" class="hover:text-blue-600 transition-colors duration-200">
            Health Blog
          </a>
        </li>
        <li><i class="bi bi-chevron-right text-slate-400"></i></li>
        <li class="text-blue-600 font-medium truncate max-w-xs">{{ Str::limit($post->title, 50) }}</li>
      </ol>
    </nav>

    {{-- Article Container --}}
    <article class="bg-white rounded-2xl shadow-xl overflow-hidden">

      {{-- Article Header --}}
      <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 px-8">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10 max-w-4xl mx-auto text-center">
          <div class="flex justify-center mb-6">
            <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold border border-white/30">
              {{ $post->category->name ?? 'General Health' }}
            </span>
          </div>
          <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6 drop-shadow-sm">
            {{ $post->title }}
          </h1>

          {{-- Author & Meta Info --}}
          <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-white/90 text-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <i class="bi bi-person-fill text-lg"></i>
              </div>
              <div>
                <div class="font-semibold">Dr. {{ $doctor->name }}</div>
                <div class="text-white/70">Medical Professional</div>
              </div>
            </div>

            <div class="flex items-center gap-4 flex-wrap justify-center">
              <span class="flex items-center gap-1">
                <i class="bi bi-calendar3"></i>
                {{ optional($post->published_at)->format('F d, Y') }}
              </span>
              <span class="flex items-center gap-1">
                <i class="bi bi-clock"></i>
                {{ $post->readTime }} min read
              </span>
              <span class="flex items-center gap-1">
                <i class="bi bi-eye"></i>
                {{ number_format($post->view_count) }} views
              </span>
            </div>
          </div>
        </div>
      </div>

      {{-- Cover Image --}}
      @if($post->cover_image)
      <div class="relative -mt-8 mx-8 mb-8">
        <div class="rounded-2xl shadow-2xl overflow-hidden">
          <img
            src="{{ $post->cover_image }}"
            alt="{{ $post->title }}"
            class="w-full h-64 md:h-96 object-cover"
            loading="lazy"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        </div>
      </div>
      @endif
      {{-- Article Content --}}
      <div class="px-6 md:px-12 py-8">
        <div class="prose prose-lg max-w-none mx-auto">
          <div class="article-content text-slate-700 leading-relaxed text-lg">
            {!! $post->body !!}
          </div>
        </div>

        {{-- Tags --}}
        @if($post->tags && count($post->tags) > 0)
        <div class="mt-12 pt-8 border-t border-slate-200">
          <h4 class="text-sm font-semibold text-slate-600 mb-4">TOPICS COVERED</h4>
          <div class="flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
            <span class="bg-slate-100 text-slate-700 px-3 py-1.5 rounded-full text-sm font-medium hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200">
              #{{ $tag }}
            </span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Share Section --}}
        <div class="mt-12 pt-8 border-t border-slate-200">
          <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
              <h4 class="text-lg font-semibold text-slate-800 mb-2">Found this helpful?</h4>
              <p class="text-slate-600">Share with others who might benefit</p>
            </div>
            @php
              $shareUrl = urlencode(request()->fullUrl());
              $shareText = urlencode($post->title);
            @endphp
           <div class="flex gap-3">
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
       target="_blank"
       class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-all hover:scale-110 shadow-lg">
        <i class="ri-facebook-fill text-xl"></i>
    </a>

    <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}"
       target="_blank"
       class="w-12 h-12 bg-slate-800 text-white rounded-full flex items-center justify-center hover:bg-black transition-all hover:scale-110 shadow-lg">
        <i class="ri-twitter-x-fill text-xl"></i>
    </a>

    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareText }}"
       target="_blank"
       class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center hover:bg-blue-600 transition-all hover:scale-110 shadow-lg">
        <i class="ri-linkedin-fill text-xl"></i>
    </a>

    <a href="mailto:?subject={{ $shareText }}&body={{ $shareUrl }}"
       class="w-12 h-12 bg-slate-600 text-white rounded-full flex items-center justify-center hover:bg-slate-700 transition-all hover:scale-110 shadow-lg">
        <i class="ri-mail-fill text-xl"></i>
    </a>
</div>

          </div>
        </div>

        {{-- Author Bio --}}
        <div class="mt-12 p-6 bg-slate-50 rounded-2xl border border-slate-200">
          <div class="flex flex-col md:flex-row items-start gap-6">
            <div class="flex-shrink-0">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                {{ substr($doctor->name, 0, 1) }}
              </div>
            </div>
            <div class="flex-1">
              <h4 class="text-xl font-bold text-slate-800 mb-2">About Dr. {{ $doctor->name }}</h4>
              <p class="text-slate-600 mb-4">
                {{ $doctor->qualification ?? 'Medical Professional' }} specializing in
                {{ $doctor->specialization ?? 'general medicine' }}.
                Committed to providing accurate health information and quality patient care.
              </p>
              <div class="flex gap-4 text-sm text-slate-500">
                @if($doctor->experience_years)
                <span class="flex items-center gap-1">
                  <i class="bi bi-award"></i>
                  {{ $doctor->experience_years }}+ years experience
                </span>
                @endif
                <span class="flex items-center gap-1">
                  <i class="bi bi-heart-pulse"></i>
                  {{ $doctor->specialization ?? 'General Practitioner' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </article>

    {{-- Related Articles --}}
    @if(isset($related) && $related->count() > 0)
    <section class="mt-16">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-slate-800 mb-4">Continue Reading</h2>
        <p class="text-slate-600 text-lg">More health insights from Dr. {{ $doctor->name }}</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($related as $r)
        <article class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
          @if($r->cover_image)
          <div class="relative overflow-hidden">
            <img
              src="{{ $r->cover_image }}"
              alt="{{ $r->title }}"
              class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            >
            <div class="absolute top-4 left-4">
              <span class="bg-white/90 backdrop-blur-sm text-slate-700 px-3 py-1 rounded-full text-xs font-semibold">
                {{ $r->category->name ?? 'Health' }}
              </span>
            </div>
          </div>
          @endif

          <div class="p-6">
            <h3 class="font-bold text-slate-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
              {{ $r->title }}
            </h3>
            <p class="text-slate-600 text-sm mb-4 line-clamp-2">
              {{ \Illuminate\Support\Str::limit(strip_tags($r->excerpt ?: $r->body), 100) }}
            </p>

            <div class="flex items-center justify-between text-sm text-slate-500">
              <span>{{ $r->readTime }} min read</span>
              <span class="flex items-center gap-1">
                <i class="bi bi-eye"></i>
                {{ number_format($r->view_count) }}
              </span>
            </div>

            <a href="{{ route('articles.show', [$doctor->id, $r->slug]) }}"
               class="mt-4 inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-200 group/btn">
              Read Article
              <i class="bi bi-arrow-right group-hover/btn:translate-x-1 transition-transform duration-200"></i>
            </a>
          </div>
        </article>
        @endforeach
      </div>
    </section>
    @endif

    {{-- Newsletter Subscription --}}
    <div class="mt-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-center text-white">
      <h3 class="text-2xl font-bold mb-4">Stay Updated with Health Tips</h3>
      <p class="text-blue-100 mb-6 max-w-md mx-auto">
        Get the latest health articles and medical insights delivered to your inbox.
      </p>
      <div class="max-w-md mx-auto flex gap-3">
        <input
          type="email"
          placeholder="Enter your email"
          class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-blue-300 text-slate-800"
        >
        <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors duration-200">
          Subscribe
        </button>
      </div>
    </div>

  </div>
</div>

<style>
  .article-content {
    font-size: 1.125rem;
    line-height: 1.75;
  }

  .article-content p {
    margin-bottom: 1.5rem;
  }

  .article-content h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 2.5rem 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
  }

  .article-content h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #334155;
    margin: 2rem 0 1rem 0;
  }

  .article-content ul, .article-content ol {
    margin: 1.5rem 0;
    padding-left: 1.5rem;
  }

  .article-content li {
    margin-bottom: 0.5rem;
  }

  .article-content blockquote {
    border-left: 4px solid #3b82f6;
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #64748b;
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 0 0.5rem 0.5rem 0;
  }

  .article-content a {
    color: #3b82f6;
    text-decoration: underline;
    text-underline-offset: 2px;
  }

  .article-content a:hover {
    color: #1d4ed8;
  }

  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  @media (max-width: 768px) {
    .article-content {
      font-size: 1rem;
      line-height: 1.7;
    }

    .article-content h2 {
      font-size: 1.5rem;
    }

    .article-content h3 {
      font-size: 1.25rem;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for anchor links within the article
    document.querySelectorAll('.article-content a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Add intersection observer for related articles animation
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Observe related articles
    document.querySelectorAll('article.group').forEach(article => {
      article.style.opacity = '0';
      article.style.transform = 'translateY(20px)';
      article.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(article);
    });
  });
</script>
@endsection
