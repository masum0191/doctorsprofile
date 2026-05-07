<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();

        if ($request->filled('search')) {
            $query->where('video_url', 'like', '%'.$request->search.'%');
        }

        $galleries = $query->latest()->paginate(15);

        return view('galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'   => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url'=> 'nullable|url',
        ]);

        $folder = 'uploads/gallery';

        foreach ($request->file('images') as $image) {

            $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path($folder), $filename);

            Gallery::create([
                'image'     => $folder.'/'.$filename,
                'video_url' => $request->video_url
            ]);
        }

        return redirect()
            ->route('admin.galleries.index')
            ->with('success','Gallery images uploaded successfully');
    }

    public function edit(Gallery $gallery)
    {
        return view('galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|url'
        ]);

        if ($request->hasFile('image')) {

            if ($gallery->image && file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }

            $folder = 'uploads/gallery';
            $filename = time().'_'.uniqid().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path($folder), $filename);

            $gallery->image = $folder.'/'.$filename;
        }

        $gallery->video_url = $request->video_url;
        $gallery->save();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success','Gallery updated successfully');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return back()->with('success','Gallery deleted successfully');
    }
}
