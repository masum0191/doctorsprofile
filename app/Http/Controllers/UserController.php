<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function updateProfileUpdateByTenant(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'name'  => 'required|string|max:255',
        'photo' => 'nullable',
        'password' => 'nullable|min:8|confirmed',
    ]);

    $doctor = User::where('email', $request->email)->first();

    if (!$doctor) {
        return response()->json([
            'status' => false,
            'message' => 'Doctor not found'
        ], 404);
    }

    /* ================= BASIC UPDATE ================= */
    $doctor->update([
        'name' => $request->name,
        'mobile' => $request->phone,
        'address' => $request->address,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'photo' => $request->photo, // Keep existing photo for now
        //'specialty' => $request->specialty,
        'specialization'=>$request->medical_specialties,
        'is_available_today' => $request->boolean('is_available_today'),
        'accepts_virtual_visits' => $request->boolean('accepts_virtual_visits'),
        'accepts_insurance' => $request->boolean('accepts_insurance'),
    ]);

    /* ================= PASSWORD ================= */
    if ($request->filled('password')) {
        $doctor->update([
            'password' => bcrypt($request->password)
        ]);
    }

    /* ================= PHOTO UPLOAD ================= */


    return response()->json([
        'status' => true,
        'message' => 'Doctor profile synced successfully'
    ]);
}

}
