<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::latest()->get();
        return view('medicines.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name',
            'type' => 'nullable|string|max:100',
            'dosage' => 'nullable|string|max:100',
            'frequency' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'instruction' => 'nullable|string',
        ]);

        Medicine::create($request->only([
            'name','type','dosage','frequency','duration','instruction'
        ]));

        return back()->with('success', 'Medicine added successfully');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' =>
                'required|string|max:255|unique:medicines,name,' . $medicine->id,
            'type' => 'nullable|string|max:100',
            'dosage' => 'nullable|string|max:100',
            'frequency' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'instruction' => 'nullable|string',
        ]);

        $medicine->update($request->only([
            'name','type','dosage','frequency','duration','instruction'
        ]));

        return back()->with('success', 'Medicine updated successfully');
    }

    public function destroy(Medicine $medicine)
    {
        // If medicine is linked with prescriptions, you may want to restrict deletion
        $medicine->delete();

        return back()->with('success', 'Medicine deleted successfully');
    }
}
