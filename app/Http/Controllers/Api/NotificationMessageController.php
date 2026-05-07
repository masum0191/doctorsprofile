<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PatientReplyMail;
use Illuminate\Support\Facades\DB;

class NotificationMessageController extends Controller
{
    // 🔔 Doctor → Patient Notification
    public function sendNotification(Request $request)
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
            'patient_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'doctor_id' => $authUser->id,
            'patient_id' => $request->patient_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Notification sent',
            'data' => $notification
        ]);
                    tenancy()->end();

    }

    // 💬 Doctor → Patient Message
    public function sendMessage(Request $request)
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
            'patient_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $msg = Message::create([
            'doctor_id' => $authUser->id,
            'patient_id' => $request->patient_id,
            'sender' => 'doctor',
            'message' => $request->message,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Message sent',
            'data' => $msg
        ]);
                    tenancy()->end();

    }

    // 📧 Patient → Doctor Reply (Email + DB)
    public function patientReply(Request $request)
    {
        $patient = request()->user();
        if (!$patient) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $tenantId = $patient->tenant_id ?? null;
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
            'message' => 'required|string',
        ]);

        // Save message
        $msg = Message::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $patient->id,
            'sender' => 'patient',
            'message' => $request->message,
        ]);

        // Email doctor
        $doctor = User::find($request->doctor_id);
        Mail::to($doctor->email)->send(
            new PatientReplyMail($request->message)
        );


        return response()->json([
            'success' => true,
            'message' => 'Reply sent to doctor',
            'data' => $msg
        ]);
                    tenancy()->end();

    }

    // 🔔 Get Doctor Notifications
    public function doctorNotifications(Request $request)
    {
        $doctor = request()->user();

        $tenantId = $doctor->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not resolved'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        tenancy()->initialize($tenant);

        $notifications = Notification::where('doctor_id', $doctor->id)
            ->latest()
            ->paginate(20);


        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
                    tenancy()->end();

    }

    // 🔔 Get Patient Notifications
    public function patientNotifications(Request $request)
    {
        $patient = request()->user();

        $tenantId = $patient->tenant_id ?? null;
        if (!$tenantId) {
            return response()->json(['message' => 'Tenant not resolved'], 422);
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        tenancy()->initialize($tenant);

        $notifications = Notification::where('patient_id', $patient->id)
            ->latest()
            ->paginate(20);


        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
                    tenancy()->end();

    }

    // 📖 Mark Notification as Read
    public function markNotificationRead($id)
    {
        $user = request()->user();

        $notification = Notification::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('doctor_id', $user->id)
                    ->orWhere('patient_id', $user->id);
            })
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    // 📜 Message Thread (Doctor ↔ Patient)
    public function messageThread(Request $request)
    {
        $user = request()->user();


        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
        ]);

        // Authorization safety
        if (
            ($user->role === 'doctor' && $user->id != $request->doctor_id) ||
            ($user->role === 'patient' && $user->id != $request->patient_id)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = Message::where('doctor_id', $request->doctor_id)
            ->where('patient_id', $request->patient_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    // 📬 Doctor Inbox (Last Message per Patient)
    public function doctorInbox()
    {
        $doctor = request()->user();

        $messages = Message::select('patient_id', DB::raw('MAX(id) as last_message_id'))
            ->where('doctor_id', $doctor->id)
            ->groupBy('patient_id')
            ->with(['patient'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    // 📬 Patient Inbox (Last Message per Doctor)
    public function patientInbox()
    {
        $patient = request()->user();

        $messages = Message::select('doctor_id', DB::raw('MAX(id) as last_message_id'))
            ->where('patient_id', $patient->id)
            ->groupBy('doctor_id')
            ->with(['doctor'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }



public function allmassege()
{
    $messages = Message::with('patient')
        ->whereIn('id', function ($query) {
            $query->select(DB::raw('MAX(id)'))
                  ->from('messages')
                  ->groupBy('patient_id');
        })
        ->orderByDesc('id')
        ->get();

    return view('setting.messege', compact('messages'));
}

public function notificationCount()
{
    $doctorId = auth()->id();

    $count = Message::where('doctor_id', $doctorId)
        ->where('sender', 'patient')
        ->whereNull('read_at') // optional if you track read status
        ->count();

    return response()->json([
        'count' => $count
    ]);
}

}
