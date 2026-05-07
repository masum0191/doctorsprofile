<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $events = Event::orderBy('publish_date', 'desc')->get();

        return response()->json($events);
                    tenancy()->end();
    }

    public function store(Request $request)
{
    $authUser = request()->user();
    if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

    $tenant = \App\Models\Tenant::find($authUser->tenant_id);
    if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

    tenancy()->initialize($tenant);

    $request->validate([
        'title' => 'required|string|max:255',
        'image_gallery.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        'video_url' => 'nullable|url',
        'publish_date' => 'nullable|date',
        'status' => 'required|boolean',
    ]);

    $imagePaths = [];

    if ($request->hasFile('image_gallery')) {
        $folder = 'uploads/events';
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        foreach ($request->file('image_gallery') as $image) {
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path($folder), $imageName);
            $imagePaths[] = $folder.'/'.$imageName;
        }
    }

    $event = \App\Models\Event::create([
        'title' => $request->title,
        'sub_title' => $request->sub_title,
        'image_gallery' => $imagePaths,
        'video_url' => $request->video_url,
        'description' => $request->description,
        'publish_date' => $request->publish_date,
        'status' => $request->status,
        'venue' => $request->venue,
    ]);


    return response()->json([
        'message' => 'Event created successfully',
        'data' => $event
    ], 201);
                tenancy()->end();

}


    public function show($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $event = Event::findOrFail($id);

        return response()->json($event);
                    tenancy()->end();

    }

    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $event = Event::findOrFail($id);
$imagePaths = [];

    if ($request->hasFile('image_gallery')) {
        $folder = 'uploads/events';
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        foreach ($request->file('image_gallery') as $image) {
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path($folder), $imageName);
            $imagePaths[] = $folder.'/'.$imageName;
        }
    }

        $event->update($request->only([
            'title',
            'sub_title',
            'image_gallery' => $imagePaths,
            'video_url',
            'description',
            'publish_date',
            'status',
            'venue',
        ]));

        return response()->json([
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
                    tenancy()->end();
    }

    public function destroy($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        Event::findOrFail($id)->delete();

        return response()->json(['message' => 'Event deleted successfully']);
                    tenancy()->end();
    }
}
