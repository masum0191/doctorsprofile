<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DoctorPost;
use App\Models\Tenant;
use App\Models\User;
use App\Services\ArticleFeed;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogController extends Controller
{
    protected function resolveRelatedPosts(DoctorPost $post, int $limit = 3)
    {
        $selected = $post->related_posts->take($limit);

        if ($selected->count() >= $limit) {
            return $selected;
        }

        $fallback = DoctorPost::published()
            ->with(['category', 'type'])
            ->where('id', '!=', $post->id)
            ->whereNotIn('id', $selected->pluck('id'))
            ->when($post->category_id, fn ($query) => $query->where('category_id', $post->category_id))
            ->orderByDesc('published_at')
            ->take($limit - $selected->count())
            ->get();

        if ($selected->count() + $fallback->count() < $limit) {
            $extra = DoctorPost::published()
                ->with(['category', 'type'])
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $selected->pluck('id'))
                ->whereNotIn('id', $fallback->pluck('id'))
                ->orderByDesc('published_at')
                ->take($limit - ($selected->count() + $fallback->count()))
                ->get();

            $fallback = $fallback->concat($extra);
        }

        return $selected->concat($fallback)->take($limit)->values();
    }

    public function index()
    {
       // $theme='tanent';
        $doctor = User::first();
        $posts = DoctorPost::published()->paginate(9);
       // dd($posts);
        return view('article', compact('doctor','posts'));

        //published()
           // ->where('user_id',$doctor->id)
          //  ->orderBy('order_column')
           // ->orderByDesc('published_at')
        //     paginate(9);

        // return view('', compact('doctor','posts'));
    }
public function allindex(Request $request, ArticleFeed $feed)
    {
        $doctor = User::first();
        $all = $feed->all();
        $perPage = 9;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $posts = new LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('all_article', compact('doctor','posts',));
    }
    public function singleshow(Request $request, $slug)
    {
        $tenantId = $request->query('tenant');

        if (!$tenantId) {
            $doctor = User::first();
            $post = DoctorPost::with(['category', 'type'])
                ->published()
                ->where('slug',$slug)
                ->firstOrFail();

            $related = $this->resolveRelatedPosts($post)
                ->each(fn ($item) => ArticleFeed::decorate($item, null, $doctor?->name));
            ArticleFeed::decorate($post, null, $doctor?->name);

            return view('single_article', compact('doctor','post', 'related'));
        }

        // Doctor article: lives in that doctor's tenant database
        $doctor = User::where('role', 'tenant')
            ->where('status', 1)
            ->where('tenant_id', $tenantId)
            ->first();
        $tenant = $doctor ? Tenant::find($tenantId) : null;
        abort_unless($tenant, 404);

        tenancy()->initialize($tenant);

        try {
            $post = DoctorPost::with(['category', 'type'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            $related = $this->resolveRelatedPosts($post)
                ->each(fn ($item) => ArticleFeed::decorate($item, $tenantId, $doctor->name));
            ArticleFeed::decorate($post, $tenantId, $doctor->name);
        } finally {
            tenancy()->end();
        }

        return view('single_article', compact('doctor','post', 'related'));
    }

    public function show(string $slug)
    {
        //dd($slug);
        $doctor = User::first();
        $post = DoctorPost::with(['category', 'type'])
            ->published()
            ->where('slug',$slug)
            ->firstOrFail();

        // You can increment views
      //  $post->increment('view_count');

        $related = $this->resolveRelatedPosts($post);

        return view('blogshow', compact('doctor','post', 'related'));
    }
}
