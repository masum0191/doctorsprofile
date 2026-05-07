<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
   public function index(Request $request)
{
    $query = Event::query();

    if ($request->filled('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('from_date')) {
        $query->whereDate('publish_date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('publish_date', '<=', $request->to_date);
    }

    $events = $query->latest()->paginate(15)->withQueryString();

    return view('events.index', compact('events'));
}
    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|url',
            'description' => 'nullable|string',
            'publish_date' => 'required|date',
            'status' => 'required|boolean',
            'venue' => 'nullable|string|max:255',
        ]);

        $gallery = [];


if ($request->hasFile('images')) {

        foreach ($request->file('images') as $image) {



            // ✅ নতুন image upload
            $folder = 'uploads/event';
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($folder), $imageName);

            $gallery[] = $folder . '/' . $imageName;


        }
    }
        Event::create([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'image_gallery' => $gallery,
            'video_url' => $request->video_url,
            'description' => $request->description,
            'publish_date' => $request->publish_date,
            'status' => $request->status,
            'venue' => $request->venue,
        ]);

        return back()->with('success', 'Event created successfully');
    }
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $gallery = $event->image_gallery ?? [];

        if ($request->hasFile('images')) {

        foreach ($request->file('images') as $image) {



            // ✅ নতুন image upload
            $folder = 'uploads/event';
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($folder), $imageName);

            $gallery[] = $folder . '/' . $imageName;


        }
    }

        $event->update([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'image_gallery' => $gallery,
            'video_url' => $request->video_url,
            'description' => $request->description,
            'publish_date' => $request->publish_date,
            'status' => $request->status,
            'venue' => $request->venue,
        ]);

        return back()->with('success', 'Event updated successfully');
    }

    public function destroy(Event $event)
    {
        // if ($event->image_gallery) {
        //     foreach ($event->image_gallery as $img) {
        //         Storage::disk('public')->delete($img);
        //     }
        // }

        $event->delete();
        return back()->with('success', 'Event deleted successfully');
    }
    public function deleteGalleryImage(Request $request, Event $event)
{
    $request->validate([
        'image' => 'required|string'
    ]);

    $gallery = $event->image_gallery ?? [];

    if (!in_array($request->image, $gallery)) {
        return response()->json(['message' => 'Image not found'], 404);
    }

    // Remove file from public folder
    if (file_exists(public_path($request->image))) {
        unlink(public_path($request->image));
    }

    // Remove from array
    $gallery = array_values(array_filter($gallery, fn ($img) => $img !== $request->image));

    $event->update([
        'image_gallery' => $gallery
    ]);

    return response()->json(['success' => true]);
}

}
