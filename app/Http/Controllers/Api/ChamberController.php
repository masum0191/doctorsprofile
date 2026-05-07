<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chamber;
use App\Models\ChamberCustomDate;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChamberController extends Controller
{
    /* -------------------------
       Tenant Resolver (Reusable)
    -------------------------- */
    private function initTenant()
    {
        $user = request()->user();
        if (!$user) {
            abort(response()->json(['message' => 'Unauthenticated'], 401));
        }

        $tenant = \App\Models\Tenant::find($user->tenant_id);
        if (!$tenant) {
            abort(response()->json(['message' => 'Tenant not found'], 404));
        }

        tenancy()->initialize($tenant);

        return $user;
    }

    /* -------------------------
       List Chambers
    -------------------------- */
    public function index()
    {
        $user = $this->initTenant();

        try {
            return response()->json(
                Chamber::latest()->get()
            );
        } finally {
            tenancy()->end();
        }
    }

    /* -------------------------
       Store Chamber (Same as WEB)
    -------------------------- */
    public function store(Request $request)
    {
        $user = $this->initTenant();
       //  return response()->json($user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'fees' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,custom',
            'schedule' => 'nullable|array',
        ]);

        try {
            $chamber = DB::transaction(function () use ($validated, $request, $user) {

                $schedule = null;

                if ($validated['type'] === 'fixed') {
                    foreach ([
                        'sunday','monday','tuesday','wednesday',
                        'thursday','friday','saturday'
                    ] as $day) {
                        $schedule[$day] = [
                            'enabled' => (bool) data_get($request, "schedule.$day.enabled"),
                            'start_time' => data_get($request, "schedule.$day.start_time", '09:00'),
                            'end_time' => data_get($request, "schedule.$day.end_time", '17:00'),
                            'slot_duration' => data_get($request, "schedule.$day.slot_duration", 30),
                        ];
                    }
                }
                $users=User::where('email',$user->email)->first();
                $chamber = Chamber::create([
                    'doctor_id' => $users->id,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'website' => $validated['website'] ?? null,
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'fees' => $validated['fees'],
                    'type' => $validated['type'],
                    'schedule' => $schedule,
                    'is_active' => true,
                ]);

                // Create sample custom dates
                if ($validated['type'] === 'custom') {
                    for ($i = 1; $i <= 7; $i++) {
                        ChamberCustomDate::create([
                            'chamber_id' => $chamber->id,
                            'date' => now()->addDays($i)->format('Y-m-d'),
                            'start_time' => '09:00:00',
                            'end_time' => '17:00:00',
                            'slot_duration' => 30,
                            'max_patients' => 20,
                            'is_active' => true,
                        ]);
                    }
                }

                return $chamber;
            });

            return response()->json([
                'message' => 'Chamber created successfully',
                'data' => $chamber
            ], 201);

        } finally {
            tenancy()->end();
        }
    }

    /* -------------------------
       Show Chamber
    -------------------------- */
    public function show($id)
    {
        $user = $this->initTenant();

        try {
            $chamber = Chamber::with('customDates')
                ->where('doctor_id', $user->id)
                ->findOrFail($id);

            return response()->json($chamber);
        } finally {
            tenancy()->end();
        }
    }

    /* -------------------------
       Update Chamber
    -------------------------- */
    public function update(Request $request, $id)
    {
        $user = $this->initTenant();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'fees' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $chamber = Chamber::where('doctor_id', $user->id)->findOrFail($id);
            $chamber->update($validated);

            return response()->json([
                'message' => 'Chamber updated successfully',
                'data' => $chamber
            ]);
        } finally {
            tenancy()->end();
        }
    }

    /* -------------------------
       Delete Chamber
    -------------------------- */
    public function destroy($id)
    {
        $user = $this->initTenant();

        try {
            Chamber::findOrFail($id)->delete();

            return response()->json(['message' => 'Chamber deleted successfully']);
        } finally {
            tenancy()->end();
        }
    }

    /* -------------------------
       Available Slots (Same as WEB)
    -------------------------- */
    public function availableSlots(Request $request, $id)
    {
        $request->validate(['date' => 'required|date']);
        $user = $this->initTenant();

        try {
            $chamber = Chamber::where('doctor_id', $user->id)->findOrFail($id);
            $date = Carbon::parse($request->date);

            if ($date->isPast() && !$date->isToday()) {
                return response()->json(['slots' => []]);
            }

            $slots = $chamber->getAvailableSlots($date->format('Y-m-d'));

            $booked = Appointment::where('chamber_id', $chamber->id)
                ->whereDate('appointment_date', $date)
                ->pluck('appointment_time')
                ->map(fn($t) => Carbon::parse($t)->format('H:i:s'))
                ->toArray();

            $available = array_filter($slots, fn($s) => !in_array($s['start'], $booked));

            return response()->json(array_values($available));
        } finally {
            tenancy()->end();
        }
    }
    public function getOnlineSlots(User $doctor, $date)
{
     $user = $this->initTenant();
    try {
        $dateObj = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
    } catch (\Exception $e) {
        return response()->json([
            'slots' => [],
            'error' => 'Invalid date format'
        ], 400);
    }

    $dayName = strtolower($dateObj->format('l'));
    $setting=Setting::first();
    $settings = $setting['online_schedule'] ?? [];

    if (!data_get($settings, 'enabled')) {
        return response()->json(['slots' => []]);
    }

    $day = data_get($settings, "working_days.$dayName");

    if (!$day || !data_get($day, 'enabled')) {
        return response()->json(['slots' => []]);
    }

    $slotDuration = max(1, (int) data_get($settings, 'slot_duration', 30));
    $buffer = max(0, (int) data_get($settings, 'buffer_minutes', 0));
    $timezone = data_get($settings, 'timezone', 'Asia/Dhaka');

    $booked = Appointment::where('doctor_id', $doctor->id)
        ->where('consultation_type', 'online')
        ->whereDate('appointment_date', $dateObj->toDateString())
        ->whereIn('status', ['confirmed','pending'])
        ->pluck('appointment_time')
        ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
        ->toArray();

    $slots = [];

    foreach (data_get($day, 'slots', []) as $range) {

        if (empty($range['from']) || empty($range['to'])) {
            continue;
        }

        $start = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dateObj->toDateString().' '.$range['from'],
            $timezone
        );

        $end = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dateObj->toDateString().' '.$range['to'],
            $timezone
        );

        while ($start->copy()->addMinutes($slotDuration) <= $end) {

            $time = $start->format('H:i');

            $available =
                !in_array($time, $booked) &&
                !($dateObj->isToday() && $start->isPast());

            $slots[] = [
                'start' => $time,
                'available' => $available
            ];

            $start->addMinutes($slotDuration + $buffer);
        }
    }

    return response()->json([
        'slots' => $slots,
        'date' => $dateObj->toDateString(),
        'type' => 'online'
    ]);
    tenancy()->end();
}
}
