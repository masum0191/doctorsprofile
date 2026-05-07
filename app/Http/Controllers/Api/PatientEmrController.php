<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatientEmr;
use Illuminate\Http\Request;

class PatientEmrController extends Controller
{
    // 📌 Add EMR
    public function store(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not resolved'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        tenancy()->initialize($tenant);
        $userId=\App\Models\User::where('email',$authUser->email)->first();
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',

            'chief_complaint' => 'nullable|string',
            'history_of_present_illness' => 'nullable|string',
            'comorbidities' => 'nullable|array',
            'comorbidities.*' => 'integer',
            'vitals' => 'nullable|array',
            'examination' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $emr = PatientEmr::create([
            'doctor_id' => $userId->id,
            'patient_id' => $request->patient_id,
            'visit_date' => $request->visit_date,

            'chief_complaint' => $request->chief_complaint,
            'history_of_present_illness' => $request->history_of_present_illness,
            'comorbidities' => $request->comorbidities,
            'vitals' => $request->vitals,
            'examination' => $request->examination,
            'diagnosis' => $request->diagnosis,
            'notes' => $request->notes,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Patient EMR created successfully',
            'data' => $emr
        ], 201);
         tenancy()->end();

    }
    // 📌 List EMRs
public function index()
{
    $authUser = request()->user();
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json(['message' => 'Tenant not resolved'], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json(['message' => 'Account not found'], 404);
    }

    tenancy()->initialize($tenant);

    $emrs = PatientEmr::latest()->get();


    return response()->json([
        'success' => true,
        'data' => $emrs
    ]);
    tenancy()->end();

}


// 📌 Show Single EMR
public function show($id)
{
    $authUser = request()->user();
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json(['message' => 'Tenant not resolved'], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json(['message' => 'Account not found'], 404);
    }

    tenancy()->initialize($tenant);

    $emr = PatientEmr::find($id);
    if (!$emr) {
        tenancy()->end();
        return response()->json(['message' => 'EMR not found'], 404);
    }


    return response()->json([
        'success' => true,
        'data' => $emr
    ]);
    tenancy()->end();

}


// 📌 Update EMR
public function update(Request $request, $id)
{
    $authUser = request()->user();
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json(['message' => 'Tenant not resolved'], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json(['message' => 'Account not found'], 404);
    }

    tenancy()->initialize($tenant);

    $emr = PatientEmr::find($id);
    if (!$emr) {
        tenancy()->end();
        return response()->json(['message' => 'EMR not found'], 404);
    }

    $request->validate([
        'visit_date' => 'sometimes|date',
        'chief_complaint' => 'nullable|string',
        'history_of_present_illness' => 'nullable|string',
        'comorbidities' => 'nullable|array',
        'comorbidities.*' => 'integer',
        'vitals' => 'nullable|array',
        'examination' => 'nullable|string',
        'diagnosis' => 'nullable|string',
        'notes' => 'nullable|string',
    ]);

    $emr->update($request->only([
        'visit_date',
        'chief_complaint',
        'history_of_present_illness',
        'comorbidities',
        'vitals',
        'examination',
        'diagnosis',
        'notes'
    ]));


    return response()->json([
        'success' => true,
        'message' => 'Patient EMR updated successfully',
        'data' => $emr
    ]);
        tenancy()->end();

}


// 📌 Delete EMR
public function destroy($id)
{
    $authUser = request()->user();
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json(['message' => 'Tenant not resolved'], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json(['message' => 'Account not found'], 404);
    }

    tenancy()->initialize($tenant);

    $emr = PatientEmr::find($id);
    if (!$emr) {
        tenancy()->end();
        return response()->json(['message' => 'EMR not found'], 404);
    }

    $emr->delete();


    return response()->json([
        'success' => true,
        'message' => 'Patient EMR deleted successfully'
    ]);
    tenancy()->end();

}

}
