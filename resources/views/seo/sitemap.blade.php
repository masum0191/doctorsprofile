{{-- resources/views/sitemap.blade.php --}}
@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>

        @if(!empty($url['lastmod']))
            <lastmod>{{ $url['lastmod'] }}</lastmod>
        @endif

        <changefreq>{{ $url['changefreq'] ?? 'weekly' }}</changefreq>
        <priority>{{ $url['priority'] ?? '0.8' }}</priority>
    </url>
@endforeach
</urlset>