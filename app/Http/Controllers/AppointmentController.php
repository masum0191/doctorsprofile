<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Chamber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {

        if($request->consultation_type == 'online'){
             $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'chamber_id' => 'nullable',
            'consultation_type' => 'required|in:online,offline',
            'service_type' => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'patient_first_name' => 'required|string|max:255',
            'patient_last_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_dob' => 'nullable|date|before:today',
            'notes' => 'nullable|string|max:1000',
            'terms_agreed' => 'required|accepted'
        ]);
        $isAvailable = $this->checkSlotAvailability(
    $validated['doctor_id'],
    $validated['appointment_date'],
    $validated['appointment_time']
);

if (!$isAvailable) {
    return response()->json([
        'success' => false,
        'message' => 'This time slot is already booked. Please choose another time.'
    ], 422);
}

        $user = User::firstOrCreate(
    ['email' => $validated['patient_email']],
    [
        'name'     => $validated['patient_first_name'] . ' ' . $validated['patient_last_name'],
        'mobile'   => $validated['patient_phone'],
        'password' => bcrypt(Str::random(10)),
        'role'     => 'patient',
        //'dob'      => $validated['patient_dob'] ?? null,
    ]
);
            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'patient_id' => $user ? $user->id : null, // null for guest bookings
                'chamber_id' => $validated['chamber_id'] ?? null,
                'consultation_type' => $validated['consultation_type'],
                'service_type' => $validated['service_type'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'appointment_duration' => '30 minutes', // default duration
                'status' => 'pending',
                'patient_first_name' => $validated['patient_first_name'],
                'patient_last_name' => $validated['patient_last_name'],
                'patient_email' => $validated['patient_email'],
                'patient_phone' => $validated['patient_phone'],
                'patient_dob' => $validated['patient_dob'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'amount' => 0,
                'currency' => 'BDT',
                'payment_status' => 'pending',
            ]);

            if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully!',
                'appointment' => [
                    'id' => $appointment->id,
                    'appointment_number' => $appointment->appointment_number ?? 'APT-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                    'appointment_date' => $appointment->appointment_date,
                    'appointment_time' => $appointment->appointment_time,
                    //'chamber_name' => $chamber->name,
                    'fees' => $appointment->amount,
                    'patient_name' => $appointment->patient_first_name . ' ' . $appointment->patient_last_name,
                    'consultation_type' => $appointment->consultation_type,
                    'service_type' => $appointment->service_type,
                ],
                'redirect_url' => route('appointment.confirmation', $appointment->id)
            ]);
        }
        return redirect()->route('appointment.confirmation', $appointment->id);
        }else{
           // $consultationType = 'Offline';
        // Validate the request
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'chamber_id' => 'nullable',
            'consultation_type' => 'required|in:online,offline',
            'service_type' => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i:s',
            'patient_first_name' => 'required|string|max:255',
            'patient_last_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_dob' => 'nullable|date|before:today',
            'notes' => 'nullable|string|max:1000',
            'terms_agreed' => 'required|accepted'
        ]);

        try {
            DB::beginTransaction();

            // Check if the time slot is still available
           $isAvailable = $this->checkSlotAvailability(
    $validated['doctor_id'],
    $validated['appointment_date'],
    $validated['appointment_time']
);

if (!$isAvailable) {
    return response()->json([
        'success' => false,
        'message' => 'This time slot is already booked. Please choose another time.'
    ], 422);
}

$user = User::firstOrCreate(
    ['email' => $validated['patient_email']],
    [
        'name'     => $validated['patient_first_name'] . ' ' . $validated['patient_last_name'],
        'mobile'   => $validated['patient_phone'],
        'password' => bcrypt(Str::random(10)),
        'role'     => 'patient',
        //'dob'      => $validated['patient_dob'] ?? null,
    ]
);

            // Get chamber details for fees
            $chamber = Chamber::findOrFail($validated['chamber_id']);
            $doctor = User::findOrFail($validated['doctor_id']);

            // Create the appointment
            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'patient_id' => $user ? $user->id : null, // null for guest bookings
                'chamber_id' => $validated['chamber_id'] ?? null,
                'consultation_type' => $validated['consultation_type'],
                'service_type' => $validated['service_type'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
                'appointment_duration' => '30 minutes', // default duration
                'status' => 'pending',
                'patient_first_name' => $validated['patient_first_name'],
                'patient_last_name' => $validated['patient_last_name'],
                'patient_email' => $validated['patient_email'],
                'patient_phone' => $validated['patient_phone'],
                'patient_dob' => $validated['patient_dob'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'amount' => $chamber->fees,
                'currency' => 'BDT',
                'payment_status' => 'pending',
            ]);

            // Generate appointment ID if not auto-increment
            if (!$appointment->id) {
                $appointment->update([
                    'appointment_number' => 'APT-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT) . '.' . rand(100, 999)
                ]);
            }

           DB::commit();

           // Send confirmation email
            try {
                Mail::to($validated['patient_email'])->send(new AppointmentConfirmation($appointment));

                // Also send notification to doctor (optional)
                // Mail::to($doctor->email)->send(new NewAppointmentNotification($appointment));

            } catch (\Exception $emailException) {
                \Log::error('Failed to send appointment email: ' . $emailException->getMessage());
                // Don't fail the appointment if email fails
            }

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Appointment booked successfully!',
            //     'appointment' => [
            //         'id' => $appointment->id,
            //         'appointment_number' => $appointment->appointment_number ?? 'APT-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
            //         'appointment_date' => $appointment->appointment_date,
            //         'appointment_time' => $appointment->appointment_time,
            //         'chamber_name' => $chamber->name,
            //         'fees' => $appointment->amount,
            //         'patient_name' => $appointment->patient_first_name . ' ' . $appointment->patient_last_name,
            //         'consultation_type' => $appointment->consultation_type,
            //         'service_type' => $appointment->service_type,
            //     ],
            //     'redirect_url' => route('appointment.confirmation', $appointment->id)
            // ]);
             if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully!',
                'appointment' => [
                    'id' => $appointment->id,
                    'appointment_number' => $appointment->appointment_number ?? 'APT-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                    'appointment_date' => $appointment->appointment_date,
                    'appointment_time' => $appointment->appointment_time,
                    //'chamber_name' => $chamber->name,
                    'fees' => $appointment->amount,
                    'patient_name' => $appointment->patient_first_name . ' ' . $appointment->patient_last_name,
                    'consultation_type' => $appointment->consultation_type,
                    'service_type' => $appointment->service_type,
                ],
                'redirect_url' => route('appointment.confirmation', $appointment->id)
            ]);
        }
        return redirect()->route('appointment.confirmation', $appointment->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Appointment booking error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to book appointment. Please try again.'
            ], 500);
        }
    }
    }

    /**
     * Check if the selected time slot is still available
     */
    private function checkSlotAvailability($chamberId, $date, $time)
    {
        $existingAppointment = Appointment::where('chamber_id', $chamberId)
            ->whereDate('appointment_date', $date)
            ->whereTime('appointment_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        return !$existingAppointment;
    }

    // confirmation
    public function confirmation($id)
    {
        $appointment = Appointment::findOrFail($id);

        return view('doctor.appointments.confirmation', compact('appointment'));
    }
}
