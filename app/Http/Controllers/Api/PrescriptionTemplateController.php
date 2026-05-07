<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrescriptionTemplate;
use Illuminate\Http\Request;

class PrescriptionTemplateController extends Controller
{
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $data = PrescriptionTemplate::latest()->get();

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
            'template_name' => 'required|string|max:255|unique:prescription_templates,template_name',
            'medicine_ids' => 'nullable|array',
            'medicine_ids.*' => 'integer',
            'investigation_ids' => 'nullable|array',
            'investigation_ids.*' => 'integer',
            'advice' => 'nullable|string',
            'next_visit' => 'nullable|date',
        ]);

        $template = PrescriptionTemplate::create([
            'template_name' => $request->template_name,
            'medicine_ids' => $request->medicine_ids,
            'investigation_ids' => $request->investigation_ids,
            'advice' => $request->advice,
            'next_visit' => $request->next_visit,
        ]);

        return response()->json([
            'message' => 'Prescription template created successfully',
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

        $template = PrescriptionTemplate::findOrFail($id);

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

        $template = PrescriptionTemplate::findOrFail($id);

        $request->validate([
            'template_name' =>
                'required|string|max:255|unique:prescription_templates,template_name,' . $id,
            'medicine_ids' => 'nullable|array',
            'medicine_ids.*' => 'integer',
            'investigation_ids' => 'nullable|array',
            'investigation_ids.*' => 'integer',
            'advice' => 'nullable|string',
            'next_visit' => 'nullable|date',
        ]);

        $template->update($request->only([
            'template_name',
            'medicine_ids',
            'investigation_ids',
            'advice',
            'next_visit',
        ]));

        return response()->json([
            'message' => 'Prescription template updated successfully',
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

        PrescriptionTemplate::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Prescription template deleted successfully'
        ]);
                    tenancy()->end();
    }
}
