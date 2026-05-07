<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DoctorPost;
use App\Models\User;

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
public function allindex()
    {
        //return 1;
       // $theme='company';
        $doctor = User::first();
        $posts = DoctorPost::published()->paginate(9);
       // dd($theme);
       // dd($posts);
        return view('all_article', compact('doctor','posts',));
    }
    public function singleshow($slug)
    {
       // dd($slug);
        //return 1;
       // $theme='company';
        $doctor = User::first();
        $post = DoctorPost::with(['category', 'type'])
            ->published()
            ->where('slug',$slug)
            ->firstOrFail();

        // You can increment views
      //  $post->increment('view_count');

        $related = $this->resolveRelatedPosts($post);

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
