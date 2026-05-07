<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FollowUpTemplate;
use Illuminate\Http\Request;

class FollowUpTemplateController extends Controller
{
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $data = FollowUpTemplate::latest()->get();

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
            'name' => 'required|string|max:255|unique:follow_up_templates,name',
            'details' => 'nullable|string',
        ]);

        $template = FollowUpTemplate::create([
            'name' => $request->name,
            'details' => $request->details,
        ]);

        return response()->json([
            'message' => 'Follow-up template created successfully',
            'data' => $template
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

        $template = FollowUpTemplate::findOrFail($id);

        return response()->json($template);
                    tenancy()->end();
    }

    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $template = FollowUpTemplate::findOrFail($id);

        $request->validate([
            'name' =>
                'required|string|max:255|unique:follow_up_templates,name,' . $id,
            'details' => 'nullable|string',
        ]);

        $template->update([
            'name' => $request->name,
            'details' => $request->details,
        ]);

        return response()->json([
            'message' => 'Follow-up template updated successfully',
            'data' => $template
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

        FollowUpTemplate::findOrFail($id)->delete();

     return response()->json([
            'message' => 'Follow-up template deleted successfully'
        ]);
                    tenancy()->end();
    }
}
