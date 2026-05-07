<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource (READ).
     */
    public function index()
    {
        $packages = Package::orderBy('sort_order')->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource (CREATE).
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage (STORE).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'storage_gb' => 'required|integer|min:1',
            'max_doctors' => 'nullable|integer|min:1',
            'max_patients' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|boolean',
            'is_visible' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['max_doctors'] = $request->filled('max_doctors') ? (int) $request->input('max_doctors') : null;
        $validated['max_patients'] = $request->filled('max_patients') ? (int) $request->input('max_patients') : null;
        $validated['features'] = $this->normalizeFeatures($request);

        Package::create($validated);

        return redirect()->route('packages.index')
                         ->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the specified resource (EDIT).
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage (UPDATE).
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'storage_gb' => 'required|integer|min:1',
            'max_doctors' => 'nullable|integer|min:1',
            'max_patients' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|boolean',
            'is_visible' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['max_doctors'] = $request->filled('max_doctors') ? (int) $request->input('max_doctors') : null;
        $validated['max_patients'] = $request->filled('max_patients') ? (int) $request->input('max_patients') : null;
        $validated['features'] = $this->normalizeFeatures($request);

        $package->update($validated);

        return redirect()->route('packages.index')
                         ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage (DELETE).
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('packages.index')

        ->with('success', 'Package deleted successfully.');
    }

    private function normalizeFeatures(Request $request): array
    {
        $keys = ['doctor', 'appointments', 'patients', 'services', 'content'];
        $features = [];

        foreach ($keys as $key) {
            $features[$key] = $request->boolean("features.$key");
        }

        return $features;
    }

    public function getPackages()
        {
            $packages = Package::all();
             return response()->json($packages);
        }
}
