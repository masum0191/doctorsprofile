@php
    $seoSettings = $settingModel ?? $setting ?? null;
    $siteName = data_get($seoSettings, 'company_name')
        ?: data_get($seoSettings, 'site_name')
        ?: config('app.name', 'DoctorsProfile');
    $defaultTitle = data_get($seoSettings, 'meta_title') ?: $siteName;
    $title = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', $defaultTitle)));
    $description = trim($__env->yieldContent(
        'meta_description',
        data_get($seoSettings, 'meta_description')
            ?: 'Find doctors, specialists, clinics, and appointment options.'
    ));
    $keywords = trim($__env->yieldContent('meta_keywords', data_get($seoSettings, 'keywords', '')));
    $robots = trim($__env->yieldContent('robots', data_get($seoSettings, 'robots') ?: 'index, follow'));
    $canonical = trim($__env->yieldContent('canonical', url()->current()));
    $ogTitle = trim($__env->yieldContent('ogtitle', $title));
    $ogDescription = trim($__env->yieldContent('ogdescription', $description));
    $ogType = trim($__env->yieldContent('ogtype', data_get($seoSettings, 'ogtype') ?: 'website'));
    $ogUrl = trim($__env->yieldContent('ogurl', $canonical));
    $explicitOgImage = trim($__env->yieldContent('ogimage', ''));
    $contextOgImage = data_get($post ?? null, 'cover_image')
        ?: data_get($doctor ?? null, 'photo')
        ?: data_get($seoSettings, 'ogimage')
        ?: data_get($seoSettings, 'logo')
        ?: 'images/og-default.jpg';
    $rawOgImage = $explicitOgImage !== '' ? $explicitOgImage : $contextOgImage;
    $ogImage = preg_match('/^https?:\/\//i', $rawOgImage) || str_starts_with($rawOgImage, '//')
        ? $rawOgImage
        : url(ltrim($rawOgImage, '/'));
    $twitterCard = trim($__env->yieldContent('twitter_card', 'summary_large_image'));
    $schemaOrganization = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $siteName,
        'url' => url('/'),
    ];
    $schemaWebsite = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteName,
        'url' => url('/'),
    ];
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords !== '')
    <meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $ogUrl }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
@include('partials.analytics-head')
<script type="application/ld+json">@json($schemaOrganization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
<script type="application/ld+json">@json($schemaWebsite, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        event: 'page_metadata',
        page_title: @json($title),
        page_description: @json($description),
        page_canonical: @json($canonical),
        page_type: @json($ogType),
        site_name: @json($siteName),
    });
</script>
@yield('meta')
@yield('data_layer')
@yield('structured_data')
