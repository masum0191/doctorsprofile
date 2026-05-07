<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\PostType;
use Illuminate\Http\Request;

class PostTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = PostType::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $postTypes = $query->orderBy('id','desc')->paginate(15);

        return view('post_types.index', compact('postTypes'));
    }

    public function create()
    {
        return view('post_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        PostType::create($request->only('name'));

        return redirect()
            ->route('admin.post-types.index')
            ->with('success','Post Type created successfully');
    }

    public function edit(PostType $postType)
    {
        return view('post_types.edit', compact('postType'));
    }

    public function update(Request $request, PostType $postType)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $postType->update($request->only('name'));

        return redirect()
            ->route('admin.post-types.index')
            ->with('success','Post Type updated successfully');
    }

    public function destroy(PostType $postType)
    {
        $postType->delete();

        return back()->with('success','Post Type deleted successfully');
    }
}
