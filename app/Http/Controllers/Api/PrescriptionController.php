<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Prescription;
use Illuminate\Support\Collection;

class PrescriptionController extends Controller
{
    public function index(Request $request)
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
    $query = Prescription::with([
        'doctor:id,name',
        'patient:id,name',
    ]);

    if ($request->doctor_id) {
        $query->where('doctor_id', $request->doctor_id);
    }

    if ($request->patient_id) {
        $query->where('patient_id', $request->patient_id);
    }

    if ($request->date) {
        $query->whereDate('prescribed_date', $request->date);
    }

    $prescriptions = $query->latest()->paginate(15);

    return response()->json($prescriptions);
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
    $prescription->load([
        'patient',
        'appointment',
        'tests',
        // If you're using pivot table for medicines
        // 'medicines' => function($query) {
        //     $query->withPivot(['dosage', 'frequency', 'duration', 'instruction']);
        // }
    ]);
    $prescription = Prescription::with([
        'doctor:id,name,email,mobile',
        'patient:id,name,age,gender,mobile',
        'appointment:id,appointment_date',
    ])->findOrFail($id);

    return response()->json([
        'status' => true,
        'data' => [
            'id' => $prescription->id,
            'prescribed_date' => $prescription->prescribed_date,
            'next_visit_date' => $prescription->next_visit_date,
            'status' => $prescription->status,

            // Doctor
            'doctor' => [
                'id' => $prescription->doctor->id,
                'name' => $prescription->doctor->name,
                'mobile' => $prescription->doctor->mobile,
            ],

            // Patient
            'patient' => [
                'id' => $prescription->patient->id,
                'name' => $prescription->patient->name,
                'age' => $prescription->patient->age,
                'gender' => $prescription->patient->gender,
                'mobile' => $prescription->patient->mobile,
            ],

            // Appointment
            'appointment' => $prescription->appointment ? [
                'id' => $prescription->appointment->id,
                'date' => $prescription->appointment->appointment_date,
            ] : null,

            // Clinical
            'chief_complaint' => $prescription->chief_complaint,
            'diagnosis' => $prescription->diagnosis,
            'instructions' => $prescription->instructions,

            // Medicines (with pivot data)
            'Medicines'=>$prescription->medicines,
            'Tests'=>$prescription->tests,


            // Tests
            
        ]
    ]);
    tenancy()->end();
}
public function medicinelistindex(Request $request)
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
    $query = Medicine::query();

    // 🔍 Search by name or type
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('type', 'like', "%{$search}%");
        });
    }

    // 📦 Optional filter by type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    $medicines = $query
        ->orderBy('name')
        ->paginate($request->get('per_page', 15));

    return response()->json([
        'status' => true,
        'data' => $medicines
    ]);
    tenancy()->end();
}
public function testindex(Request $request)
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
    $query = Test::query();

    // 🔍 Search by test name
    if ($request->filled('search')) {
        $query->where('test_name', 'like', '%' . $request->search . '%');
    }

    $tests = $query
        ->orderBy('test_name')
        ->paginate($request->get('per_page', 15));

    return response()->json([
        'status' => true,
        'data' => $tests
    ]);
    tenancy()->end();
}
}
