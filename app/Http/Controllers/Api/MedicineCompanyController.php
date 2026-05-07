<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicineCompany;
use Illuminate\Http\Request;

class MedicineCompanyController extends Controller
{
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $data = MedicineCompany::latest()->get();

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
            'company_name' => 'required|string|max:255|unique:medicine_companies,company_name'
        ]);

        $company = MedicineCompany::create([
            'company_name' => $request->company_name
        ]);

        return response()->json([
            'message' => 'Medicine company created successfully',
            'data' => $company
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

        $company = MedicineCompany::findOrFail($id);

        return response()->json($company);
                    tenancy()->end();

    }

    public function update(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message' => 'Unauthenticated'], 401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message' => 'Tenant not found'], 404);

        tenancy()->initialize($tenant);

        $company = MedicineCompany::findOrFail($id);

        $request->validate([
            'company_name' =>
                'required|string|max:255|unique:medicine_companies,company_name,' . $id
        ]);

        $company->update([
            'company_name' => $request->company_name
        ]);

        return response()->json([
            'message' => 'Medicine company updated successfully',
            'data' => $company
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

        MedicineCompany::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Medicine company deleted successfully'
        ]);
                    tenancy()->end();

    }
}
