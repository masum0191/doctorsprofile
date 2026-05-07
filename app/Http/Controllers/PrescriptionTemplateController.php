<?php

namespace App\Http\Controllers;

use App\Models\PrescriptionTemplate;
use App\Models\MedicineTemplate;
use App\Models\Test;
use Illuminate\Http\Request;

class PrescriptionTemplateController extends Controller
{
    // public function index()
    // {
    //     $test=Test::get();
    //     $medicine_templates = MedicineTemplate::latest()->get();
    //     $prescriptions_template = PrescriptionTemplate::latest()->get();
    //     //dd($prescriptions_template);
    //     return view('setting.prescription', compact('medicine_templates', 'prescriptions_template','test'));
    // }
public function index(Request $request)
{
    $test = Test::get();
    $medicine_templates = MedicineTemplate::latest()->get();
    $prescriptions_template = PrescriptionTemplate::latest()->get();
    
    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'prescriptions_template' => $prescriptions_template,
            'medicine_templates' => $medicine_templates,
            'tests' => $test
        ]);
    }
    
    // Return HTML view for regular requests
    return view('setting.prescription', compact('medicine_templates', 'prescriptions_template', 'test'));
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'chief_complaint' => 'nullable|string',
            'diagnosis_details' => 'nullable|string',
            'medicine_ids' => 'nullable|array',
            'medicine_ids.*' => 'integer|exists:medicine_templates,id',
            'investigation_ids' => 'nullable|array',
            'investigation_ids.*' => 'string',
            'advice' => 'nullable|string',
            'next_visit' => 'nullable|string',
            'diet_advice' => 'nullable|string',
            'patient_instructions' => 'nullable|string',
        ]);

        // Convert arrays to JSON
        $validated['medicine_ids'] = $validated['medicine_ids'] ?? [];
        $validated['investigation_ids'] = $validated['investigation_ids'] ?? [];

        PrescriptionTemplate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Prescription template saved successfully!'
        ]);
    }

    public function update(Request $request, $id)
    {
        $template = PrescriptionTemplate::findOrFail($id);

        $validated = $request->validate([
            'template_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'chief_complaint' => 'nullable|string',
            'diagnosis_details' => 'nullable|string',
            'medicine_ids' => 'nullable|array',
            'medicine_ids.*' => 'integer|exists:medicine_templates,id',
            'investigation_ids' => 'nullable|array',
            'investigation_ids.*' => 'string',
            'advice' => 'nullable|string',
            'next_visit' => 'nullable|string',
            'diet_advice' => 'nullable|string',
            'patient_instructions' => 'nullable|string',
        ]);

        // Convert arrays to JSON
        $validated['medicine_ids'] = $validated['medicine_ids'] ?? [];
        $validated['investigation_ids'] = $validated['investigation_ids'] ?? [];

        $template->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Prescription template updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $template = PrescriptionTemplate::findOrFail($id);
        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prescription template deleted successfully!'
        ]);
    }

    public function show($id)
    {
        $template = PrescriptionTemplate::findOrFail($id);

        // Get medicines with details
        $medicineDetails = [];
        if (!empty($template->medicine_ids)) {
            $medicineDetails = MedicineTemplate::whereIn('id', $template->medicine_ids)->get();
        }

        return response()->json([
            'template' => $template,
            'medicine_details' => $medicineDetails
        ]);
    }

    public function searchMedicines(Request $request)
    {
        $search = $request->input('search', '');

        $medicines = MedicineTemplate::where('medicine_name', 'like', "%{$search}%")
            ->orWhere('generic_name', 'like', "%{$search}%")
            ->limit(20)
            ->get();

        return response()->json($medicines);
    }

    public function searchTests(Request $request)
{
    $search = $request->input('search', '');

    $tests = Test::where('test_name', 'like', "%{$search}%")
      //  ->orWhere('test_category', 'like', "%{$search}%")
        ->limit(20)
        ->get();

    return response()->json($tests);
}

public function getTest($id)
{
    $test = Test::findOrFail($id);
    return response()->json($test);
}
}
