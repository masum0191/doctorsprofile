<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorTelemedicinePlatform;
use Illuminate\Http\Request;

class TelemedicineController extends Controller
{
    public function index(Request $request)
{
    $doctor = $request->user();

    $query = DoctorTelemedicinePlatform::where('user_id', $doctor->id);

    // Text search by name
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where('name', 'like', "%{$q}%");
    }

    // Active status filter: all / active / inactive
    if ($request->filled('active') && $request->active !== 'all') {
        $query->where('active', $request->active === '1');
    }

    $platforms = $query
        ->orderBy('order_column')
        ->get();

    return view('doctor.telemedicine.index', [
        'platforms' => $platforms,
        'filters'   => $request->only('q', 'active'),
    ]);
}

public function destroy(DoctorTelemedicinePlatform $platform)
{
    $platform->delete();

    return redirect()->route('admin.telemedicine.index')->with('ok', 'Platform deleted successfully.');
}
}
