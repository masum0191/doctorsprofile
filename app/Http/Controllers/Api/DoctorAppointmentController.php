<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorAppointmentController extends Controller
{
    // 📌 Appointment List
    public function index(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);
        $doctorId=User::where('email',$request->user()->email)->first()->id;
        $query = Appointment::with(['patient','chamber'])
            ->where('doctor_id', $doctorId)
            ->latest();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('patient_first_name','like','%'.$request->search.'%')
                  ->orWhere('patient_last_name','like','%'.$request->search.'%')
                  ->orWhere('patient_phone','like','%'.$request->search.'%')
                  ->orWhere('appointment_number','like','%'.$request->search.'%');
            });
        }

        $appointments = $query->paginate(20);

        return response()->json($appointments);
        tenancy()->end();

    }

    // 📌 Online Appointments
    public function onlineAppointments(Request $request)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);
        $doctorId=User::where('email',$request->user()->email)->first()->id;
        $appointments = Appointment::with(['patient','chamber'])
            ->where('doctor_id',$doctorId)
            ->where('consultation_type','online')
            ->latest()
            ->paginate(20);

        return response()->json($appointments);
                tenancy()->end();

    }

    // 📌 Show Appointment
    public function show($id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);
       // $doctorId=User::where('email',$authUser->email)->first()->id;
       $appointment = Appointment::with(['patient','chamber'])
    ->where('id', $id)
    ->first();

        return response()->json($appointment);
                tenancy()->end();

    }

    // 📌 Update Status
    public function updateStatus(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled,no_show'
        ]);

        DB::beginTransaction();

        try {
            $appointment = Appointment::findOrFail($id);

            $appointment->update([
                'status' => $request->status,
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            if ($request->status === 'confirmed') {
                $appointment->update(['confirmed_at'=>now()]);
            } elseif ($request->status === 'completed') {
                $appointment->update(['completed_at'=>now()]);
            } else {
                $appointment->update(['cancelled_at'=>now()]);
            }

            DB::commit();

            return response()->json([
                'message'=>'Status updated successfully',
                'data'=>$appointment
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message'=>'Failed'],500);
        }
        tenancy()->end();

    }

    // 📌 Reschedule
    public function reschedule(Request $request, $id)
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $request->validate([
            'appointment_date'=>'required|date|after_or_equal:today',
            'appointment_time'=>'required|date_format:H:i:s'
        ]);

        $appointment = Appointment::where('id', $id)->first();

        $exists = Appointment::whereDate('appointment_date',$request->appointment_date)
            ->whereTime('appointment_time',$request->appointment_time)
            ->where('id','!=',$appointment->id)
            ->whereIn('status',['pending','confirmed'])
            ->exists();

        if ($exists) {
            return response()->json(['message'=>'Slot not available'],422);
        }

        $appointment->update([
            'appointment_date'=>$request->appointment_date,
            'appointment_time'=>$request->appointment_time,
            'rescheduled_at'=>now(),
            'reschedule_reason'=>$request->reason,
        ]);

        return response()->json([
            'message'=>'Appointment rescheduled',
            'data'=>$appointment
        ]);
        tenancy()->end();
    }

    // 📌 Calendar API
    public function calendar()
    {
        $authUser = request()->user();
        if (!$authUser) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($authUser->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);

        $appointments = Appointment::with(['patient','chamber'])
            // ->where('doctor_id',$authUser->id)
            ->whereIn('status',['pending','confirmed'])
            ->get()
            ->map(function($a){
                return [
                    'id'=>$a->id,
                    'title'=>$a->patient_full_name,
                    'start'=>$a->appointment_date.'T'.$a->appointment_time,
                    'end'=>$a->appointment_date.'T'.Carbon::parse($a->appointment_time)->addMinutes(30),
                    'status'=>$a->status,
                ];
            });

        return response()->json($appointments);
        tenancy()->end();
    }
}
