<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $query = Slider::query();

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->q.'%')
                  ->orWhere('sub_title', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sliders = $query->orderBy('order')->paginate(15)->withQueryString();

        return view('sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('sliders.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

       if ($request->hasFile('image')) {

    $image = $request->file('image');

    // Generate unique filename
    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

    // Move to public/sliders
    $image->move(public_path('sliders'), $filename);

    // Save relative path in DB
    $data['image'] = 'sliders/' . $filename;
}


        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully');
    }

    public function edit(Slider $slider)
    {
        return view('sliders.create', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $this->validateData($request);

       if ($request->hasFile('image')) {

    $image = $request->file('image');

    // Generate unique filename
    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

    // Move to public/sliders
    $image->move(public_path('sliders'), $filename);

    // Save relative path in DB
    $data['image'] = 'sliders/' . $filename;
}
  

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();
        return back()->with('success', 'Slider deleted');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'title'        => 'nullable|string|max:255',
            'sub_title'    => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'video_url'    => 'nullable|url',
            'click_url'    => 'nullable|url',
            'button_text'  => 'nullable|string|max:50',
            'target'       => 'required|in:_self,_blank',
            'status'       => 'required|boolean',
            'order'        => 'nullable|integer',
            'start_at'     => 'nullable|date',
            'end_at'       => 'nullable|date|after_or_equal:start_at',
        ]);
    }
}
