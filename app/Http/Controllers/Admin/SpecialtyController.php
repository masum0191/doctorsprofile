<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::orderByDesc('id')->paginate(20);
        return view('admin.specialties.index', compact('specialties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120|unique:specialties,name',
            'description' => 'nullable|string','icon' => 'nullable|string','color' => 'nullable|string',
        ]);

        Specialty::create($data);

        return back()->with('success', 'Specialty created successfully');
    }

    public function update(Request $request, Specialty $specialty)
    {
        $data = $request->validate([
            'name' =>
                'required|string|max:120|unique:specialties,name,' . $specialty->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $specialty->update($data);

        return back()->with('success', 'Specialty updated successfully');
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->delete();

        return back()->with('success', 'Specialty deleted successfully');
    }
}
