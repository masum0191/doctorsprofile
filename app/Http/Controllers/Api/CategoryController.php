<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function index(Request $request)
    {
         $authUser = request()->user(); // Sanctum-safe
        //return response()->json($authUser);
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);

        return response()->json(
            Category::with('children')
                ->whereNull('parent_id')
                ->get()
        );
tenancy()->end();
    }

    public function store(Request $request)

    {
        $authUser = request()->user(); // Sanctum-safe

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'

        ]);

        $category = Category::create($request->only('name', 'parent_id'));

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
tenancy()->end();
    }

    public function show($id)
    {
        $authUser = request()->user(); // Sanctum-safe

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $category = Category::with('children', 'parent')->findOrFail($id);

    return response()->json($category);
     tenancy()->end();
}

    public function update(Request $request, $id)
    {
        $authUser = request()->user(); // Sanctum-safe

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $id,
        ]);

        $category->update($request->only('name', 'parent_id'));

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
tenancy()->end();
    }

    public function destroy($id)
    {
        $authUser = request()->user(); // Sanctum-safe

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
        Category::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
tenancy()->end();
    }
}
