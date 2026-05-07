<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class PatientController extends Controller
{
    public function index(Request $request)
{
    //return 1;
    $doctor = $request->user(); // logged-in doctor
    $search = $request->input('q');

    $query = User::query()
        ->where('role', 'patient') // ✅ ONLY PATIENTS
        ->withCount([
            'patientAppointments as total_appointments' => function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            }
        ])
        ->with([
            'patientAppointments' => function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id)
                  ->orderByDesc('appointment_date')
                  ->orderByDesc('appointment_time');
            }
        ]);

    // 🔍 SEARCH
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $patients = $query
        ->orderByDesc('created_at') // newest patients first
        ->paginate(15)
        ->withQueryString();

    return view('doctor.patients.index', compact('doctor', 'patients', 'search'));
}



    public function store(Request $request)
{
    $validated = Validator::make($request->all(), [

        'name'    => ['required', 'string', 'max:255'],
        'age'     => ['nullable', 'string'],
        'mobile'  => ['required', 'string', 'max:20'],
        'gender'  => ['required', 'in:male,female,other'],
        'address' => ['required', 'string'],
        'email'   => ['nullable', 'email', 'max:255', 'unique:users,email'],

        'vitality' => ['nullable', 'string'],

        // Emergency Contact (JSON)
        'emergency_contact' => ['nullable', 'array'],
        'emergency_contact.name'         => ['required_with:emergency_contact', 'string'],
        'emergency_contact.relationship' => ['required_with:emergency_contact', 'string'],
        'emergency_contact.contact'      => ['required_with:emergency_contact', 'string'],

        // Basic Details (JSON)
        'basic_details' => ['nullable', 'array'],
        'basic_details.blood_group' => ['nullable', 'string'],
        'basic_details.height'      => ['nullable', 'numeric'],
        'basic_details.weight'      => ['nullable', 'numeric'],

        'medical_history' => ['nullable', 'string'],

    ])->validate();

    $patient = User::create([
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

        'role'     => 'patient',
        'password' => bcrypt(Str::random(10)),
    ]);
// ✅ AJAX / API REQUEST
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Patient added successfully',
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'age' => $patient->age,
                'gender' => $patient->gender,
                'phone' => $patient->mobile,
                'last_visit' => null,
                'avatar' => strtoupper(substr($patient->name, 0, 1)),
            ]
        ], 201);
    }
    return redirect()
        ->back()
        ->with('success', 'Patient added successfully.');
}

public function update(Request $request, $id)
{
    $patient = User::findOrFail($id);

    $validated = Validator::make($request->all(), [

        'name'    => ['required', 'string', 'max:255'],
        'age'     => ['nullable', 'string'],
        'mobile'  => ['required', 'string', 'max:20'],
        'gender'  => ['required', 'in:male,female,other'],
        'address' => ['required', 'string'],

        'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $patient->id],

        'vitality' => ['nullable', 'string'],

        // Emergency Contact (JSON)
        'emergency_contact' => ['nullable', 'array'],
        'emergency_contact.name'         => ['required_with:emergency_contact', 'string'],
        'emergency_contact.relationship' => ['required_with:emergency_contact', 'string'],
        'emergency_contact.contact'      => ['required_with:emergency_contact', 'string'],

        // Basic Details (JSON)
        'basic_details' => ['nullable', 'array'],
        'basic_details.blood_group' => ['nullable', 'string'],
        'basic_details.height'      => ['nullable', 'numeric'],
        'basic_details.weight'      => ['nullable', 'numeric'],

        'medical_history' => ['nullable', 'string'],

    ])->validate();

    $patient->update([
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
    ]);

    return redirect()
        ->back()
        ->with('success', 'Patient updated successfully.');
}


 /**
     * 👤 VIEW PATIENT PROFILE
     */
    public function profile(User $patient)
    {
        abort_if($patient->role !== 'patient', 404);

        return view('doctor.patients.profile', compact('patient'));
    }

    /**
     * 📅 VISIT HISTORY (APPOINTMENTS)
     */
    public function history(User $patient)
    {
        abort_if($patient->role !== 'patient', 404);

        $appointments = Appointment::where('patient_id', $patient->id)
            ->latest('appointment_date')
            ->get();

        return view('doctor.patients.history', compact('patient', 'appointments'));
    }

    /**
     * 🧾 MEDICAL RECORDS (PRESCRIPTIONS)
     */
    public function medicalRecords(User $patient)
    {
        abort_if($patient->role !== 'patient', 404);

        $prescriptions = Prescription::with(['doctor', 'tests', 'medicines'])
            ->where('patient_id', $patient->id)
            ->latest('prescribed_date')
            ->get();

        return view('doctor.patients.records', compact('patient', 'prescriptions'));
    }

}
