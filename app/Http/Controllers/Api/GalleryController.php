<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{

    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not resolved.'
            ], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not found.'
            ], 404);
        }

        tenancy()->initialize($tenant);

        try {

            $data = Gallery::latest()->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } finally {
            tenancy()->end();
        }
    }


    public function store(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not resolved.'
            ], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not found.'
            ], 404);
        }

        tenancy()->initialize($tenant);

        try {

            $request->validate([
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'video_url' => 'nullable|url',
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {

                $folder = 'uploads/gallery';

                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }

                $file = $request->file('image');
                $imageName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path($folder), $imageName);

                $imagePath = $folder.'/'.$imageName;
            }

            $gallery = Gallery::create([
                'image' => $imagePath,
                'video_url' => $request->video_url,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gallery item created successfully',
                'data' => $gallery
            ], 201);

        } finally {
            tenancy()->end();
        }
    }


    public function show($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not resolved.'
            ], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not found.'
            ], 404);
        }

        tenancy()->initialize($tenant);

        try {

            $gallery = Gallery::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $gallery
            ]);

        } finally {
            tenancy()->end();
        }
    }


    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not resolved.'
            ], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not found.'
            ], 404);
        }

        tenancy()->initialize($tenant);

        try {

            $gallery = Gallery::findOrFail($id);

            $request->validate([
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'video_url' => 'nullable|url',
            ]);

            if ($request->hasFile('image')) {

                if ($gallery->image && file_exists(public_path($gallery->image))) {
                    unlink(public_path($gallery->image));
                }

                $folder = 'uploads/gallery';

                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }

                $file = $request->file('image');
                $imageName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path($folder), $imageName);

                $gallery->image = $folder.'/'.$imageName;
            }

            $gallery->video_url = $request->video_url;
            $gallery->save();

            return response()->json([
                'success' => true,
                'message' => 'Gallery item updated successfully',
                'data' => $gallery
            ]);

        } finally {
            tenancy()->end();
        }
    }


    public function destroy($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not resolved.'
            ], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not found.'
            ], 404);
        }

        tenancy()->initialize($tenant);

        try {

            $gallery = Gallery::findOrFail($id);

            if ($gallery->image && file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }

            $gallery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gallery item deleted successfully'
            ]);

        } finally {
            tenancy()->end();
        }
    }

}