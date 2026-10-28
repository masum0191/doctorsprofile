<?php

namespace App\Services;

use App\Models\DoctorPost;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Public article feed: admin (central) posts + published posts from every active doctor tenant.
 */
class ArticleFeed
{
    public const CACHE_KEY = 'public_article_feed';

    protected int $perTenantLimit = 50;
    protected int $cacheSeconds = 300;

    public function all(): Collection
    {
        return Cache::remember(self::CACHE_KEY, $this->cacheSeconds, fn () => $this->build());
    }

    public function latest(int $limit): Collection
    {
        return $this->all()->take($limit)->values();
    }

    public static function urlFor(DoctorPost $post, ?string $tenantId = null): string
    {
        $url = url('singles-article/' . $post->slug);

        return $tenantId ? $url . '?tenant=' . urlencode($tenantId) : $url;
    }

    /**
     * Prepare a post for public views: absolute cover image, author name and article link.
     */
    public static function decorate(DoctorPost $post, ?string $tenantId, ?string $authorName): DoctorPost
    {
        $post->setAttribute('source_tenant_id', $tenantId);
        $post->setAttribute('author_name', $authorName ?: 'Doctors Profile');
        $post->setAttribute('article_url', static::urlFor($post, $tenantId));

        $cover = $post->cover_image;
        if ($cover && !preg_match('/^(https?:)?\/\//i', $cover)) {
            $post->cover_image = url(ltrim($cover, '/'));
        }

        return $post;
    }

    protected function build(): Collection
    {
        $posts = collect();

        $centralAuthor = User::first()?->name;
        try {
            DoctorPost::published()->with('category')->get()
                ->each(fn ($post) => $posts->push(static::decorate($post, null, $centralAuthor)));
        } catch (Throwable $e) {
            Log::warning('Article feed: central posts failed', ['message' => $e->getMessage()]);
        }

        $doctors = User::where('role', 'tenant')
            ->where('status', 1)
            ->whereNotNull('tenant_id')
            ->get()
            ->keyBy('tenant_id');

        $tenants = Tenant::whereIn('id', $doctors->keys())->get();

        foreach ($tenants as $tenant) {
            $doctor = $doctors->get($tenant->id);

            try {
                tenancy()->initialize($tenant);

                DoctorPost::published()
                    ->with('category')
                    ->orderByDesc('published_at')
                    ->take($this->perTenantLimit)
                    ->get()
                    ->each(fn ($post) => $posts->push(static::decorate($post, $tenant->id, $doctor?->name)));
            } catch (Throwable $e) {
                Log::warning('Article feed: tenant posts failed', [
                    'tenant_id' => $tenant->id,
                    'message' => $e->getMessage(),
                ]);
            } finally {
                tenancy()->end();
            }
        }

        return $posts
            ->sortByDesc(fn ($post) => optional($post->published_at ?? $post->created_at)->timestamp ?? 0)
            ->values();
    }
}
