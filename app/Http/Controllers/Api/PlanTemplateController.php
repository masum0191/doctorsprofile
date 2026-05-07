<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanTemplate;
use Illuminate\Http\Request;

class PlanTemplateController extends Controller
{
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $data = PlanTemplate::latest()->get();

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
            'plan_name' => 'required|string|max:255|unique:plan_templates,plan_name',
            'plan_details' => 'nullable|string',
        ]);

        $plan = PlanTemplate::create([
            'plan_name' => $request->plan_name,
            'plan_details' => $request->plan_details,
        ]);

        return response()->json([
            'message' => 'Plan template created successfully',
            'data' => $plan
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

        $plan = PlanTemplate::findOrFail($id);

        return response()->json($plan);
                    tenancy()->end();

    }

    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $plan = PlanTemplate::findOrFail($id);

        $request->validate([
            'plan_name' =>
                'required|string|max:255|unique:plan_templates,plan_name,' . $id,
            'plan_details' => 'nullable|string',
        ]);

        $plan->update([
            'plan_name' => $request->plan_name,
            'plan_details' => $request->plan_details,
        ]);

        return response()->json([
            'message' => 'Plan template updated successfully',
            'data' => $plan
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

        PlanTemplate::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Plan template deleted successfully'
        ]);
                    tenancy()->end();

    }
}
