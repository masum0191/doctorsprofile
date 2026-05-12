<?php

namespace App\Http\Controllers;

use App\Models\DoctorPost;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $content = implode(PHP_EOL, [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /superadmin',
            'Disallow: /dashboard',
            'Disallow: /api',
            'Sitemap: ' . url('/sitemap.xml'),
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $staticUrls = collect([
            ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('finds'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('articles.index'), 'changefreq' => 'daily', 'priority' => '0.8'],
            ['loc' => route('package.index'), 'changefreq' => 'monthly', 'priority' => '0.6'],
        ]);

        $doctorUrls = User::query()
            ->where('role', 'tenant')
            ->where('status', 1)
            ->orderBy('id')
            ->get(['id', 'name', 'updated_at'])
            ->map(fn (User $doctor) => [
                'loc' => route('doc-details', [
                    'doctor' => $doctor->id,
                    'slug' => Str::slug($doctor->name),
                ]),
                'lastmod' => optional($doctor->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ]);

        $specialtyUrls = Specialty::query()
            ->orderBy('id')
            ->get(['id', 'updated_at'])
            ->map(fn (Specialty $specialty) => [
                'loc' => route('specialty.doctors', ['slug' => $specialty->id]),
                'lastmod' => optional($specialty->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ]);

        $articleUrls = DoctorPost::published()
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn (DoctorPost $post) => [
                'loc' => url('singles-article/' . $post->slug),
                'lastmod' => optional($post->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ]);

        $urls = $staticUrls
            ->concat($doctorUrls)
            ->concat($specialtyUrls)
            ->concat($articleUrls)
            ->values();

        return response()
            ->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
