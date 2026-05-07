<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicineTemplate;
use Illuminate\Http\Request;

class MedicineTemplateController extends Controller
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
        return response()->json(MedicineTemplate::latest()->get());
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
            'medicine_name' => 'required|string|max:255',
            'medicine_url'  => 'nullable|url',
        ]);

        $medicine = MedicineTemplate::create($request->all());

        return response()->json([
            'message' => 'Medicine template created successfully',
            'data' => $medicine
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
            MedicineTemplate::findOrFail($id)
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
        $medicine = MedicineTemplate::findOrFail($id);

        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'medicine_url'  => 'nullable|url',
        ]);

        $medicine->update($request->all());

        return response()->json([
            'message' => 'Medicine template updated successfully',
            'data' => $medicine
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
        MedicineTemplate::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Medicine template deleted successfully'
        ]);
    tenancy()->end();
    }
}
