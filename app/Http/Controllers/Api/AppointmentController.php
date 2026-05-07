<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Chamber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AppointmentConfirmation;

class AppointmentController extends Controller
{
    // 📌 Book Appointment (Public API)
   public function store(Request $request)
{
    $authUser = request()->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $tenantId = $authUser->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not resolved'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        tenancy()->initialize($tenant);

    $request->validate([
        'doctor_id' => 'required|exists:users,id',
        'chamber_id' => 'required|exists:chambers,id',
        'patient_id' => 'nullable|exists:users,id',

        'consultation_type' => 'required|in:online,offline',
        'service_type' => 'required|string|max:255',
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required|date_format:H:i:s',

        'patient_first_name' => 'required_without:patient_id|string|max:255',
        'patient_last_name'  => 'required_without:patient_id|string|max:255',
        'patient_email'      => 'required_without:patient_id|email|max:255',
        'patient_phone'      => 'required_without:patient_id|string|max:20',

        'patient_dob' => 'nullable|date|before:today',
        'notes' => 'nullable|string|max:1000',
        'terms_agreed' => 'required|accepted',
    ]);

    DB::beginTransaction();

    try {
        // 🔹 Check slot availability
        $slotTaken = Appointment::where('chamber_id', $request->chamber_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($slotTaken) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'This time slot is no longer available'
            ], 422);
        }

        // 🔹 Resolve patient
        if ($request->filled('patient_id')) {

            $patient = User::where('id', $request->patient_id)
                ->where('role', 'patient')
                ->first();

            if (!$patient) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid patient ID'
                ], 422);
            }

        } else {

            $patient = User::firstOrCreate(
                ['email' => $request->patient_email],
                [
                    'name'     => $request->patient_first_name . ' ' . $request->patient_last_name,
                    'mobile'   => $request->patient_phone,
                    'password' => bcrypt(Str::random(10)),
                    'role'     => 'patient',
                ]
            );
        }

        // 🔹 Get chamber for fees
        $chamber = Chamber::findOrFail($request->chamber_id);

        // 🔹 Create appointment
        $appointment = Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $patient->id,
            'chamber_id' => $request->chamber_id,

            'consultation_type' => $request->consultation_type,
            'service_type' => $request->service_type,

            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'appointment_duration' => 30,

            'status' => 'pending',

            'patient_first_name' => $patient->name ?? ($request->patient_first_name . ' ' . $request->patient_last_name),
            'patient_last_name' => $patient->name ?? $request->patient_last_name,
            'patient_email' => $patient->email ?? $request->patient_email,
            'patient_phone' => $patient->mobile ?? $request->patient_phone,
            'patient_dob' => $request->patient_dob,

            'notes' => $request->notes,

            'amount' => $chamber->fees,
            'currency' => 'BDT',
            'payment_status' => 'pending',
        ]);

        // 🔹 Appointment number
        $appointment->update([
            'appointment_number' => 'APT-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT)
        ]);

        DB::commit();

        // 🔹 Send confirmation email (non-blocking)
        try {
            Mail::to($appointment->patient_email)
                ->send(new AppointmentConfirmation($appointment));
        } catch (\Exception $e) {
            \Log::warning('Appointment email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully',
            'data' => [
                'appointment_id' => $appointment->id,
                'appointment_number' => $appointment->appointment_number,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'patient_name' => $appointment->patient_first_name,
                'consultation_type' => $appointment->consultation_type,
                'fees' => $appointment->amount,
            ]
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Appointment booking error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to book appointment'
        ], 500);
    }
    tenancy()->end();
}


    // 🔹 Slot availability check
    private function checkSlotAvailability($chamberId, $date, $time)
    {
        return !Appointment::where('chamber_id', $chamberId)
            ->whereDate('appointment_date', $date)
            ->whereTime('appointment_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }
}
