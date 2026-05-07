<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorPostRequest;
use App\Models\DoctorPost;
use App\Models\Category;
use App\Models\PostType;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class DoctorPostController extends Controller
{
    public function index(Request $request)
    {
        //$doctor = User::first();
        $posts = DoctorPost::orderByDesc('published_at')->with('category','type')
            ->orderBy('order_column')
            ->paginate(12);
        //dd($posts);
        return view('doctor.blog.index', compact('posts'));
    }

    public function create()
    {
        $post = new DoctorPost();
        $category= Category::get();
        $type=PostType::get();
        $relatedPosts = DoctorPost::orderByDesc('published_at')->orderByDesc('id')->get(['id', 'title']);
        return view('doctor.blog.form', compact('post','category','type', 'relatedPosts'));
    }

    public function store(DoctorPostRequest $request)
    {
       // dd($request->all());
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        // slug
        if (empty($data['slug'])) $data['slug'] = DoctorPost::makeSlug($data['title']);
        if ($request->hasFile('cover_image')) {
                $folder = 'uploads/doctor-posts';
                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }

                $image = $request->file('cover_image');
                $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path($folder), $imageName);
                $coverImagePath = $folder.'/'.$imageName;
        }
        DoctorPost::create($data + ['cover_image' => $coverImagePath ?? null]);
        return redirect()->route('admin.posts.index')->with('ok','Post created.');
    }

    public function edit(DoctorPost $post, Request $request)
    {
        abort_unless($post->user_id === $request->user()->id, 403);
         $category= Category::get();
        $type=PostType::get();
        $relatedPosts = DoctorPost::where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get(['id', 'title']);
        return view('doctor.blog.form', compact('post','category','type', 'relatedPosts'));
    }

    public function update(DoctorPostRequest $request, DoctorPost $post)
    {
        abort_unless($post->user_id === $request->user()->id, 403);
        $data = $request->validated();

        if (empty($data['slug']) || $data['title'] !== $post->title) {
            $data['slug'] = DoctorPost::makeSlug($data['title']);
        }

        $post->update($data);
        return redirect()->route('admin.posts.index')->with('ok','Post updated.');
    }

    public function destroy(DoctorPost $post, Request $request)
    {
        abort_unless($post->user_id === $request->user()->id, 403);
        $post->delete();
        return back()->with('ok','Post deleted.');
    }
}
