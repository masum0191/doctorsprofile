<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comorbidity;
use Illuminate\Http\Request;

class ComorbidityController extends Controller
{
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $data = Comorbidity::latest()->get();
        return response()->json($data);

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
            'comorbidity_name' => 'required|string|max:255|unique:comorbidities,comorbidity_name'
        ]);

        $item = Comorbidity::create([
            'comorbidity_name' => $request->comorbidity_name
        ]);
return response()->json([
            'message' => 'Comorbidity created successfully',
            'data' => $item
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

        $item = Comorbidity::findOrFail($id);

        return response()->json($item);
        tenancy()->end();

    }

    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $item = Comorbidity::findOrFail($id);

        $request->validate([
            'comorbidity_name' =>
                'required|string|max:255|unique:comorbidities,comorbidity_name,' . $id
        ]);

        $item->update([
            'comorbidity_name' => $request->comorbidity_name
        ]);

        return response()->json([
            'message' => 'Comorbidity updated successfully',
            'data' => $item
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

        Comorbidity::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Comorbidity deleted successfully'
        ]);
        tenancy()->end();
    }
}
