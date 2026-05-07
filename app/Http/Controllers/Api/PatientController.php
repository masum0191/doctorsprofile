<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PatientController extends Controller
{

    private function resolveTenant()
    {
        $authUser = request()->user();

        if (!$authUser) {
            abort(response()->json(['message' => 'Unauthenticated'], 401));
        }

        $tenantId = $authUser->tenant_id ?? null;

        if (!$tenantId) {
            abort(response()->json([
                'success' => false,
                'message' => 'Tenant not resolved.'
            ], 422));
        }

        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$tenant) {
            abort(response()->json([
                'success' => false,
                'message' => 'Your account is not found.'
            ], 404));
        }

        tenancy()->initialize($tenant);

        return $authUser;
    }


    /**
     * GET ALL PATIENTS
     */
    public function index(Request $request)
    {
        $this->resolveTenant();

        try {

            $patients = User::where('role','patient')
                ->latest()
                ->paginate(20);

            return response()->json([
                'success'=>true,
                'data'=>$patients
            ]);

        } finally {
            tenancy()->end();
        }
    }



    /**
     * CREATE PATIENT
     */
    public function store(Request $request)
    {
        $authUser = $this->resolveTenant();

        try {

            $validated = Validator::make($request->all(), [

                'name'    => ['required','string','max:255'],
                'age'     => ['nullable','string'],
                'mobile'  => ['required','string','max:20'],
                'gender'  => ['required','in:male,female,other'],
                'address' => ['required','string'],
                'email'   => ['nullable','email','unique:users,email'],

                'vitality' => ['nullable','string'],

                'emergency_contact' => ['nullable','array'],
                'emergency_contact.name' => ['required_with:emergency_contact'],
                'emergency_contact.relationship' => ['required_with:emergency_contact'],
                'emergency_contact.contact' => ['required_with:emergency_contact'],

                'basic_details' => ['nullable','array'],
                'basic_details.blood_group' => ['nullable','string'],
                'basic_details.height' => ['nullable','numeric'],
                'basic_details.weight' => ['nullable','numeric'],

                'medical_history' => ['nullable','string'],

            ])->validate();


            $patient = User::create([

              //  'tenant_id' => $authUser->tenant_id,
                'doctor_id' => $authUser->id,

                'name'    => $validated['name'],
                'age'     => $validated['age'] ?? null,
                'mobile'  => $validated['mobile'],
                'gender'  => $validated['gender'],
                'address' => $validated['address'],
                'email'   => $validated['email'] ?? null,

                'vitality'          => $validated['vitality'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'basic_details'     => $validated['basic_details'] ?? null,
                'medical_history'   => $validated['medical_history'] ?? null,

                'role' => 'patient',
                'password' => bcrypt(Str::random(10))

            ]);

            return response()->json([
                'success'=>true,
                'message'=>'Patient created successfully',
                'data'=>$patient
            ],201);

        } finally {
            tenancy()->end();
        }
    }



    /**
     * SHOW SINGLE PATIENT
     */
    public function show($id)
    {
        $this->resolveTenant();

        try {

            $patient = User::where('role','patient')->findOrFail($id);

            return response()->json([
                'success'=>true,
                'data'=>$patient
            ]);

        } finally {
            tenancy()->end();
        }
    }



    /**
     * UPDATE PATIENT
     */
    public function update(Request $request,$id)
    {
        $this->resolveTenant();

        try {

            $patient = User::findOrFail($id);

            $validated = Validator::make($request->all(), [

                'name'    => ['required','string','max:255'],
                'age'     => ['nullable','string'],
                'mobile'  => ['required','string','max:20'],
                'gender'  => ['required','in:male,female,other'],
                'address' => ['required','string'],
                'email'   => ['nullable','email','unique:users,email,'.$patient->id],

            ])->validate();


            $patient->update($validated);

            return response()->json([
                'success'=>true,
                'message'=>'Patient updated successfully',
                'data'=>$patient
            ]);

        } finally {
            tenancy()->end();
        }
    }



    /**
     * DELETE PATIENT
     */
    public function destroy($id)
    {
        $this->resolveTenant();

        try {

            $patient = User::findOrFail($id);

            $patient->delete();

            return response()->json([
                'success'=>true,
                'message'=>'Patient deleted successfully'
            ]);

        } finally {
            tenancy()->end();
        }
    }



    /**
     * PATIENT APPOINTMENT HISTORY
     */
    public function history($id)
    {
        $this->resolveTenant();

        try {

            $appointments = Appointment::where('patient_id',$id)
                ->latest('appointment_date')
                ->get();

            return response()->json([
                'success'=>true,
                'data'=>$appointments
            ]);

        } finally {
            tenancy()->end();
        }
    }



    /**
     * PATIENT MEDICAL RECORDS
     */
    public function records($id)
    {
        $this->resolveTenant();

        try {

            $records = Prescription::with(['doctor','tests','medicines'])
                ->where('patient_id',$id)
                ->latest('prescribed_date')
                ->get();

            return response()->json([
                'success'=>true,
                'data'=>$records
            ]);

        } finally {
            tenancy()->end();
        }
    }

}