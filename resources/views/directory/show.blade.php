{{-- resources/views/directory/show.blade.php --}}
@extends('layouts.sass')
@php
  $directoryTitle = $settings->site_name ?? $tenant->name ?? 'LocalGov';
  $directoryDescription = Str::limit(
      strip_tags($settings->extras['about'] ?? $settings->extras['tagline'] ?? $tenant->name ?? ''),
      160
  );
  $directoryUrl = route('tenants.show', $tenant->slug);
  $directorySchema = [
      '@context' => 'https://schema.org',
      '@type' => 'Organization',
      'name' => $directoryTitle,
      'url' => $directoryUrl,
      'description' => $directoryDescription,
  ];
@endphp

@section('title', $directoryTitle)
@section('meta_description', $directoryDescription)
@section('canonical', $directoryUrl)
@section('ogtitle', $directoryTitle)
@section('ogdescription', $directoryDescription)
@section('ogurl', $directoryUrl)

@section('structured_data')
  <script type="application/ld+json">
      @json($directorySchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
  </script>
@endsection

@section('content')
<main class="max-w-5xl mx-auto px-4 py-10">
  <h1 class="text-3xl font-semibold">{{ $settings->site_name ?? $tenant->name }}</h1>
  <p class="text-slate-600 mt-1">
    {{ ucfirst(str_replace('_',' ', $tenant->type ?? '')) }}
  </p>

  <div class="mt-6 grid sm:grid-cols-3 gap-3 text-sm">
    <div class="rounded border p-3">
      <div class="text-slate-500">Wards</div>
      <div class="text-lg font-semibold">{{ $settings->extras['wards_count'] ?? 0 }}</div>
    </div>
    <div class="rounded border p-3">
      <div class="text-slate-500">Villages</div>
      <div class="text-lg font-semibold">{{ $settings->extras['village_count'] ?? 0 }}</div>
    </div>
    <div class="rounded border p-3">
      <div class="text-slate-500">Population</div>
      <div class="text-lg font-semibold">{{ $settings->extras['population'] ?? 0 }}</div>
    </div>
  </div>

  @php
    $domain = optional($tenant->domains()->first())->domain;
  @endphp
  <div class="mt-6">
    @if($domain)
      <a class="text-sky-700 hover:underline" target="_blank" rel="noopener"
         href="https://{{ $domain }}">{{ $domain }}</a>
    @else
      <span class="text-slate-400">No website</span>
    @endif
  </div>

  @if(!empty($settings->extras['about']))
    <div class="prose mt-6">
      {!! nl2br(e($settings->extras['about'])) !!}
    </div>
  @endif
</main>
@endsection
