<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DoctorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth()->user();

        $query = Appointment::with(['patient', 'chamber'])
            ->where('doctor_id', $doctor->id)
            ->latest();

        // Filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('patient_first_name', 'like', "%{$search}%")
                  ->orWhere('patient_last_name', 'like', "%{$search}%")
                  ->orWhere('patient_phone', 'like', "%{$search}%")
                  ->orWhere('appointment_number', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(20);

        $stats = [
            'total' => Appointment::where('doctor_id', $doctor->id)->count(),
            'pending' => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'confirmed' => Appointment::where('doctor_id', $doctor->id)->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
            'cancelled' => Appointment::where('doctor_id', $doctor->id)->where('status', 'cancelled')->count(),
            'today' => Appointment::where('doctor_id', $doctor->id)->whereDate('appointment_date', today())->count(),
        ];

        return view('doctor.appointments.index', compact('appointments', 'stats'));
    }
    // online appointments list
    public function onlineAppointments(Request $request)
    {
        $doctor = auth()->user();

        $query = Appointment::with(['patient', 'chamber'])
            ->where('doctor_id', $doctor->id)
            ->where('consultation_type', 'online')
            ->latest();

        // Filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('patient_first_name', 'like', "%{$search}%")
                  ->orWhere('patient_last_name', 'like', "%{$search}%")
                  ->orWhere('patient_phone', 'like', "%{$search}%")
                  ->orWhere('appointment_number', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(20);

        $stats = [
            'total' => Appointment::where('doctor_id', $doctor->id)->where('consultation_type', 'online')->count(),
            'pending' => Appointment::where('doctor_id', $doctor->id)->where('consultation_type', 'online')->where('status', 'pending')->count(),
            'confirmed' => Appointment::where('doctor_id', $doctor->id)->where('consultation_type', 'online')->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('doctor_id', $doctor->id)->where('consultation_type', 'online')->where('status', 'completed')->count(),
            'cancelled' => Appointment::where('doctor_id', $doctor->id)->where('consultation_type', 'online')->where('status', 'cancelled')->count(),
            'today' => Appointment::where('doctor_id', $doctor->id)->where('consultation_type', 'online')->whereDate('appointment_date', today())->count(),
        ];

        return view('doctor.appointments.index', compact('appointments', 'stats'));
    }

    public function show(Appointment $appointment)
    {
        // Check if appointment belongs to doctor
        if ($appointment->doctor_id !== auth()->user()->id) {
            abort(403);
        }

        $appointment->load(['patient', 'chamber']);

        return view('doctor.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== auth()->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled,no_show'
        ]);

        try {
            DB::beginTransaction();

            $appointment->update([
                'status' => $request->status,
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Update timestamps based on status
            if ($request->status === 'confirmed') {
                $appointment->update(['confirmed_at' => now()]);
            } elseif ($request->status === 'completed') {
                $appointment->update(['completed_at' => now()]);
            } elseif (in_array($request->status, ['cancelled', 'no_show'])) {
                $appointment->update(['cancelled_at' => now()]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully',
                'appointment' => $appointment->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment status'
            ], 500);
        }
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        //return response()->json(['data' => $request->all()]);

        if ($appointment->doctor_id !== auth()->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

       $request->validate([
    'appointment_date' => 'required|date|after_or_equal:today',
    'appointment_time' => 'required|date_format:H:i',
    'reason' => 'nullable|string|max:500'
]);


        try {
            DB::beginTransaction();

            // Check if new time slot is available
            $isSlotAvailable = Appointment::where('doctor_id', $appointment->doctor_id)
                ->whereDate('appointment_date', $request->appointment_date)
                ->whereTime('appointment_time', $request->appointment_time)
                ->where('id', '!=', $appointment->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->doesntExist();

            if (!$isSlotAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected time slot is not available'
                ], 422);
            }

            $appointment->update([
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'reschedule_reason' => $request->reason,
                'rescheduled_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Appointment rescheduled successfully',
                'appointment' => $appointment->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reschedule appointment'
            ], 500);
        }
    }

    // public function calendar()
    // {
    //     $doctor = auth()->user();

    //     $appointments = Appointment::with(['patient', 'chamber'])
    //         ->where('doctor_id', $doctor->id)
    //         ->whereIn('status', ['pending', 'confirmed'])
    //         ->whereDate('appointment_date', '>=', today())
    //         ->get()
    //         ->map(function ($appointment) {
    //             return [
    //                 'id' => $appointment->id,
    //                 'title' => $appointment->patient_full_name . ' - ' . $appointment->chamber->name,
    //                 'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
    //                 'end' => $appointment->appointment_date->format('Y-m-d') . 'T' . Carbon::parse($appointment->appointment_time)->addMinutes(30)->format('H:i:s'),
    //                 'className' => $this->getStatusClass($appointment->status),
    //                 'extendedProps' => [
    //                     'appointment_number' => $appointment->appointment_number,
    //                     'patient_phone' => $appointment->patient_phone,
    //                     'consultation_type' => $appointment->consultation_type,
    //                     'status' => $appointment->status,
    //                 ]
    //             ];
    //         });

    //     return view('doctor.appointments.calendar', compact('appointments'));
    // }


public function calendar()
{
    $doctor = auth()->user();

    // Get appointment statistics
    $todayAppointments = Appointment::where('doctor_id', $doctor->id)
        ->whereDate('appointment_date', today())
        ->whereIn('status', ['pending', 'confirmed'])
        ->count();

    $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
        ->where('status', 'pending')
        ->whereDate('appointment_date', '>=', today())
        ->count();

    $confirmedAppointments = Appointment::where('doctor_id', $doctor->id)
        ->where('status', 'confirmed')
        ->whereDate('appointment_date', '>=', today())
        ->count();

    $thisWeekAppointments = Appointment::where('doctor_id', $doctor->id)
        ->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])
        ->whereIn('status', ['pending', 'confirmed'])
        ->count();

    // Get appointments for calendar
    $appointments = Appointment::with(['patient', 'chamber'])
        ->where('doctor_id', $doctor->id)
        ->whereIn('status', ['pending', 'confirmed'])
        ->whereDate('appointment_date', '>=', today()->subDays(7)) // Show last 7 days for context
        ->get()
        ->map(function ($appointment) {
            $duration = $appointment->duration ?? 30; // Default 30 minutes

            return [
                'id' => $appointment->id,
                'title' => $appointment->patient->full_name ?? $appointment->patient_full_name,
                'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                'end' => $appointment->appointment_date->format('Y-m-d') . 'T' .
                         Carbon::parse($appointment->appointment_time)->addMinutes($duration)->format('H:i:s'),
                'className' => $this->getStatusClass($appointment->status),
                'extendedProps' => [
                    'appointment_number' => $appointment->appointment_number,
                    'patient_name' => $appointment->patient->full_name ?? $appointment->patient_full_name,
                    'patient_phone' => $appointment->patient_phone,
                    'patient_email' => $appointment->patient_email,
                    'consultation_type' => $appointment->consultation_type,
                    'status' => $appointment->status,
                    'chamber_name' => $appointment->chamber->name ?? 'N/A',
                    'chamber_address' => $appointment->chamber->address ?? 'N/A',
                    'amount' => $appointment->amount,
                    'notes' => $appointment->notes,
                    'duration' => $duration
                ]
            ];
        });

    return view('doctor.appointments.calendar', compact(
        'appointments',
        'todayAppointments',
        'pendingAppointments',
        'confirmedAppointments',
        'thisWeekAppointments'
    ));
}

// private function getStatusClass($status)
// {
//     $classes = [
//         'pending' => 'fc-event-pending',
//         'confirmed' => 'fc-event-confirmed',
//         'completed' => 'fc-event-completed',
//         'cancelled' => 'fc-event-cancelled'
//     ];

//     return $classes[$status] ?? 'fc-event-pending';
// }
    public function upcoming()
    {
        $doctor = auth()->user()->doctor;

        $appointments = Appointment::with(['patient', 'chamber'])
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        return view('doctor.appointments.upcoming', compact('appointments'));
    }

    public function today()
    {
        $doctor = auth()->user();

        $appointments = Appointment::with(['patient', 'chamber'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_time')
            ->get();

        $completedToday = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();

        return view('doctor.appointments.today', compact('appointments', 'completedToday'));
    }

    private function getStatusClass($status)
    {
        $classes = [
            'pending' => 'bg-warning text-dark',
            'confirmed' => 'bg-primary text-white',
            'completed' => 'bg-success text-white',
            'cancelled' => 'bg-danger text-white',
            'no_show' => 'bg-secondary text-white',
        ];

        return $classes[$status] ?? 'bg-info text-white';
    }
}
