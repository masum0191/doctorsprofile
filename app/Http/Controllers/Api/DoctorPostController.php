<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorPostRequest;
use App\Models\DoctorPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DoctorPostController extends Controller
{
    /* =========================
       Tenant Bootstrap
    ========================== */
    private function initTenant(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            abort(response()->json(['message' => 'Unauthenticated'], 401));
        }

        $tenant = \App\Models\Tenant::find($user->tenant_id);
        if (!$tenant) {
            abort(response()->json(['message' => 'Tenant not found'], 404));
        }

        tenancy()->initialize($tenant);
        return $user;
    }

    /* =========================
       INDEX – List Posts
    ========================== */
    public function index(Request $request)
    {
        $user = $this->initTenant($request);

        try {
            $posts = DoctorPost::with(['category', 'type'])
             //   ->where('user_id', $user->id)
                ->orderBy('order_column')
                ->latest()
                ->get();

            return response()->json($posts);
        } finally {
            tenancy()->end();
        }
    }

    /* =========================
       STORE – Create Post
    ========================== */
    public function store(DoctorPostRequest $request)
    {
        $user = $this->initTenant($request);

        try {
            $coverImagePath = null;

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
            $users=User::where('email',$user->email)->first();
            $post = DoctorPost::create([
                'user_id'       => $users->id,
                'title'         => $request->title,
                'slug'          => $request->slug ?? Str::slug($request->title).'-'.uniqid(),
                'category_id'   => $request->category_id,
                'type_id'       => $request->type_id,
                'cover_image'   => $coverImagePath,
                'excerpt'       => $request->excerpt,
                'body'          => $request->body,
                'read_minutes'  => $request->read_minutes,
                'is_published'  => $request->boolean('is_published'),
                'published_at'  => $request->boolean('is_published')
                                    ? ($request->published_at ?? now())
                                    : null,
                'meta_title'        => $request->meta_title,
                'meta_description'  => $request->meta_description,
                'meta_keywords'     => $request->meta_keywords,
                'order_column'      => $request->order_column ?? 0,
            ]);

            return response()->json([
                'message' => 'Post created successfully',
                'data'    => $post
            ], 201);

        } finally {
            tenancy()->end();
        }
    }

    /* =========================
       SHOW – Single Post
    ========================== */
    public function show(Request $request, $id)
    {
        $user = $this->initTenant($request);

        try {
            $post = DoctorPost::with(['category', 'type'])
                ->where('user_id', $user->id)
                ->findOrFail($id);

            return response()->json($post);
        } finally {
            tenancy()->end();
        }
    }

    /* =========================
       UPDATE – Edit Post
    ========================== */
    public function update(Request $request, $id)
    {
        // return response()->json($request->all());
        $user = $this->initTenant($request);

        try {
            $post = DoctorPost::findOrFail($id);

            $coverImagePath = $post->cover_image;

            if ($request->hasFile('cover_image')) {
                if ($post->cover_image && file_exists(public_path($post->cover_image))) {
                    unlink(public_path($post->cover_image));
                }

                $folder = 'uploads/doctor-posts';
                $image = $request->file('cover_image');
                $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path($folder), $imageName);
                $coverImagePath = $folder.'/'.$imageName;
            }

            $post->update([
                'title'         => $request->title,
                'slug'          => $request->slug ?? Str::slug($request->title).'-'.$post->id,
                'category_id'   => $request->category_id,
                'type_id'       => $request->type_id,
                'cover_image'   => $coverImagePath,
                'excerpt'       => $request->excerpt,
                'body'          => $request->body,
                'read_minutes'  => $request->read_minutes,
                'is_published'  => $request->boolean('is_published'),
                'published_at'  => $request->boolean('is_published')
                                    ? ($request->published_at ?? now())
                                    : null,
                'meta_title'        => $request->meta_title,
                'meta_description'  => $request->meta_description,
                'meta_keywords'     => $request->meta_keywords,
                'order_column'      => $request->order_column ?? 0,
            ]);

            return response()->json([
                'message' => 'Post updated successfully',
                'data'    => $post
            ]);

        } finally {
            tenancy()->end();
        }
    }

    /* =========================
       DESTROY – Delete Post
    ========================== */
    public function destroy(Request $request, $id)
    {
        $user = $this->initTenant($request);

        try {
            DoctorPost::where('user_id', $user->id)->findOrFail($id)->delete();
            return response()->json(['message' => 'Post deleted successfully']);
        } finally {
            tenancy()->end();
        }
    }
}
