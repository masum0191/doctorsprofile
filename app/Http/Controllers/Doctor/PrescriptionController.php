<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\PrescriptionTemplate;
use App\Models\MedicineTemplate;
use App\Models\Test;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    // In your PrescriptionController
// public function add_new(){
//     $patients = User::where('role', 'patient')
//         ->select('id', 'name', 'email', 'mobile', 'age', 'gender', 'address')
//         ->latest()
//         ->take(20)
//         ->get();

//     // Transform patients for frontend
//     $patients = $patients->map(function($patient) {
//         return [
//             'id' => $patient->id,
//             'name' => $patient->name,
//             'phone' => $patient->mobile ?? '',
//             'blood_group' => $patient->blood_group ?? 'Unknown', // Note: you don't have blood_group field
//             'age' => $patient->age ?? '--',
//             'gender' => $patient->gender ?? '--',
//             'last_visit' => $patient->patientPrescriptions()->latest()->first()->created_at ?? null,
//             'avatar' => strtoupper(substr($patient->name, 0, 1))
//         ];
//     });

//     $prescriptions_template = PrescriptionTemplate::latest()->get();

//     // Get medicines and tests from database
//     $medicines = MedicineTemplate::orderBy('medicine_name')
//         ->get();

//     $tests = Test::select('id', 'test_name as name')
//         ->orderBy('test_name')
//         ->get();

//     return view('doctor.prescriptions.addnew')
//         ->with('patients', $patients)
//         ->with('prescriptions_template', $prescriptions_template)
//         ->with('medicines', $medicines)
//         ->with('tests', $tests);
// }
public function add_new(){
    $patients = User::where('role', 'patient')
        ->select('id', 'name', 'email', 'mobile', 'age', 'gender', 'address')
        ->latest()
        ->take(20)
        ->get();

    // Transform patients for frontend
    $patients = $patients->map(function($patient) {
        return [
            'id' => $patient->id,
            'name' => $patient->name,
            'phone' => $patient->mobile ?? '',
            'age' => $patient->age ?? '--',
            'gender' => $patient->gender ?? '--',
            'last_visit' => $patient->patientPrescriptions()->latest()->first()->created_at ?? null,
            'avatar' => strtoupper(substr($patient->name, 0, 1))
        ];
    });

    $prescriptions_template = PrescriptionTemplate::latest()->get();
    $medicines = MedicineTemplate::orderBy('medicine_name')->get();
    $tests = Test::select('id', 'test_name as name')->orderBy('test_name')->get();

    return view('doctor.prescriptions.addnew', compact('patients', 'prescriptions_template', 'medicines', 'tests'));
}
public function prescriptionstore(Request $request)
{
  // dd($request->all());
    $validated = $request->validate([
        'doctor_id' => 'required|exists:users,id',
        'patient_id' => 'required|exists:users,id',
        'prescribed_date' => 'required|date',
        'chief_complaint' => 'nullable|string',
        'diagnosis' => 'nullable|string',
        'instructions' => 'nullable|string',
        'next_visit_date' => 'nullable|date',
        'diet_advice' => 'nullable|string',
        'medicines' => 'nullable|array',
        
        'tests' => 'nullable|array',
    ]);

    //DB::beginTransaction();
    
   // try {
        // Create prescription
        $prescription = Prescription::create([
            'doctor_id' => auth()->id(),
            'patient_id' => $validated['patient_id'],
            'prescribed_date' => $validated['prescribed_date'],
            'chief_complaint' => $validated['chief_complaint'] ?? null,
            'diagnosis' => $validated['diagnosis'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'next_visit_date' => $validated['next_visit_date'] ?? null,
            'diet_advice' => $validated['diet_advice'] ?? null,
            'medicines'=>$validated['medicines'] ?? null,
            'tests'=>$validated['tests'] ?? null,

            'status' => 'active',
        ]);

        // Attach medicines with pivot data
        

      //  DB::commit();

        return redirect()->route('admin.patients.prescriptions.show', $prescription->id)
            ->with('success', 'Prescription created successfully.');

    // } catch (\Exception $e) {
    //     DB::rollBack();
    //     Log::error('Prescription creation failed: ' . $e->getMessage());
    //     return redirect()->back()
    //         ->withInput()
    //         ->with('error', 'Failed to create prescription. Please try again.');
    // }
}
    public function create(Request $request, User $patient)
    {
        $doctor = $request->user();

        // Optional: last appointment of this patient with this doctor
        $lastAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->first();

        return view('doctor.prescriptions.create', compact('doctor', 'patient', 'lastAppointment'));
    }

    public function store(Request $request, User $patient)
    {
        $doctor = $request->user();

        $data = $request->validate([
            'appointment_id'   => 'nullable|exists:appointments,id',
            'prescribed_date'  => 'nullable|date',
            'chief_complaint'  => 'nullable|string|max:255',
            'diagnosis'        => 'nullable|string',
            'instructions'     => 'nullable|string',
            'medicines'        => 'nullable|string',
            'next_visit_date'  => 'nullable|string|max:255',
        ]);

        $data['doctor_id'] = $doctor->id;
        $data['patient_id'] = $patient->id;

        if (empty($data['prescribed_date'])) {
            $data['prescribed_date'] = now()->toDateString();
        }

        Prescription::create($data);

        return redirect()
            ->route('admin.patients.index')
            ->with('ok', 'Prescription saved successfully.');
    }

    public function index(Request $request, User $patient)
    {
        $doctor = $request->user();
        $patient = Appointment::where('patient_id', $patient->id)->first();
        $prescriptions = Prescription::with('appointment')
            ->where('doctor_id', $doctor->id)
            //->where('patient_id', $patient->id)
            ->orderByDesc('prescribed_date')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('doctor.prescriptions.index', compact('doctor', 'prescriptions'));
    }

    public function show(Request $request, Prescription $prescription)
{
    $doctor = $request->user();

    if ($prescription->doctor_id !== $doctor->id) {
        abort(403);
    }

    // Load all necessary relationships
    $prescription->load([
        'patient',
        'appointment',
        'tests',
        // If you're using pivot table for medicines
        // 'medicines' => function($query) {
        //     $query->withPivot(['dosage', 'frequency', 'duration', 'instruction']);
        // }
    ]);

    return view('doctor.prescriptions.show', compact('doctor', 'prescription'));
}

}
