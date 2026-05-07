<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    // List
    public function index()
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

        return response()->json(Test::latest()->get());
    tenancy()->end();
    }

    // Create
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
            'investigation_name' => 'required|string|unique:investigations,investigation_name'
        ]);

        $investigation = Test::create($request->only('investigation_name'));

        return response()->json([
            'message' => 'Investigation created successfully',
            'data' => $investigation
        ], 201);
    tenancy()->end();
    }

    // Show
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
        return response()->json(
            Test::findOrFail($id)
        );
    tenancy()->end();
    }

    // Update
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
        $investigation = Test::findOrFail($id);

        $request->validate([
            'investigation_name' =>
                'required|string|unique:investigations,investigation_name,' . $id
        ]);

        $investigation->update($request->only('investigation_name'));

        return response()->json([
            'message' => 'Investigation updated successfully',
            'data' => $investigation
        ]);
    tenancy()->end();
    }

    // Delete
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
       Test ::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Investigation deleted successfully'
        ]);
    tenancy()->end();
    }
}

