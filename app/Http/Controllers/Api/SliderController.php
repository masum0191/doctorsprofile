<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant not resolved.'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Your account is not found.'], 404);
        }

        tenancy()->initialize($tenant);

        $sliders = Slider::orderBy('order')->get();

        return response()->json($sliders);
        tenancy()->end();

    }

    public function store(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant not resolved.'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Your account is not found.'], 404);
        }

        tenancy()->initialize($tenant);

        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'video_url' => 'nullable|url',
            'status' => 'required|boolean',
            'order' => 'nullable|integer',
            'button_text'=>'nullable'

        ]);
 $imagePath = null;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $folder = 'uploads/sliders';
        $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
        $image->move(public_path($folder), $imageName);
        $imagePath = $folder.'/'.$imageName;
    }

        $slider = Slider::create(array_merge($request->all(), ['image' => $imagePath]));

        return response()->json([
            'message' => 'Slider created successfully',
            'data' => $slider
        ], 201);
    tenancy()->end();

    }

    public function show($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant not resolved.'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Your account is not found.'], 404);
        }

        tenancy()->initialize($tenant);

        $slider = Slider::findOrFail($id);

        return response()->json($slider);
        tenancy()->end();

    }

    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant not resolved.'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Your account is not found.'], 404);
        }

        tenancy()->initialize($tenant);

        $slider = Slider::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'video_url' => 'nullable|url',
            'status' => 'required|boolean',
            'order' => 'nullable|integer',
        ]);

        $imagePath = $slider->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $folder = 'uploads/sliders';
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path($folder), $imageName);
            $imagePath = $folder.'/'.$imageName;
        }

        $slider->update(array_merge($request->all(), ['image' => $imagePath]));

        return response()->json([
            'message' => 'Slider updated successfully',
            'data' => $slider
        ]);
        tenancy()->end();

    }

    public function destroy($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant not resolved.'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Your account is not found.'], 404);
        }

        tenancy()->initialize($tenant);

        Slider::findOrFail($id)->delete();

        return response()->json(['message' => 'Slider deleted successfully']);
        tenancy()->end();

    }
}
