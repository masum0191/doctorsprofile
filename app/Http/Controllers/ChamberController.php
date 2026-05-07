<?php

namespace App\Http\Controllers;

use App\Models\Chamber;
use App\Models\ChamberCustomDate;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ChamberController extends Controller
{
    public function index()
    {
        $chambers = Chamber::get();
        return view('chambers.index', compact('chambers'));
    }

    public function create()
    {
        return view('chambers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'website' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'fees' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,custom',
        ]);

        DB::transaction(function () use ($request) {
            $scheduleData = [];

            if ($request->type === 'fixed') {
                // Process schedule data with proper boolean values
                foreach (['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day) {
                    $scheduleData[$day] = [
                        'enabled' => isset($request->schedule[$day]['enabled']) && $request->schedule[$day]['enabled'] == '1',
                        'start_time' => $request->schedule[$day]['start_time'] ?? '09:00',
                        'end_time' => $request->schedule[$day]['end_time'] ?? '17:00',
                        'slot_duration' => $request->schedule[$day]['slot_duration'] ?? 30,
                    ];
                }
            }

            $chamber = Chamber::create([
                'doctor_id' => auth()->id(),
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'fees' => $request->fees,
                'type' => $request->type,
                'schedule' => $request->type === 'fixed' ? $scheduleData : null,
            ]);

            // If custom type, create sample custom dates
            if ($request->type === 'custom') {
                $this->createSampleCustomDates($chamber);
            }
        });

        return redirect()->route('admin.chambers.index')->with('success', 'Chamber created successfully.');
    }

    public function edit(Chamber $chamber)
    {
        // Check if user owns this chamber
        if ($chamber->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('chambers.edit', compact('chamber'));
    }

    public function update(Request $request, Chamber $chamber)
    {
        // Check if user owns this chamber
        if ($chamber->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'fees' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $chamber->update($request->all());

        return redirect()->route('admin.chambers.index')->with('success', 'Chamber updated successfully.');
    }

    public function destroy(Chamber $chamber)
    {
        // Check if user owns this chamber
        if ($chamber->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $chamber->delete();

        return redirect()->route('admin.chambers.index')->with('success', 'Chamber deleted successfully.');
    }

    // Manage custom dates for custom-type chambers
    public function customDates(Chamber $chamber)
    {
        // Check if user owns this chamber
        if ($chamber->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $customDates = $chamber->customDates()->orderBy('date', 'desc')->get();

        return view('chambers.custom-dates', compact('chamber', 'customDates'));
    }

    public function storeCustomDate(Request $request, Chamber $chamber)
{
    // Check if user owns this chamber
    if ($chamber->doctor_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'date' => 'required|date|after:today',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'slot_duration' => 'required|integer|min:15|max:120',
        'max_patients' => 'nullable|integer|min:1',
        'is_active' => 'boolean',
    ]);

    // Check if date already exists for this chamber
    $existingDate = ChamberCustomDate::where('chamber_id', $chamber->id)
        ->where('date', $request->date)
        ->first();

    if ($existingDate) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['date' => 'A custom date already exists for this date. Please edit the existing one instead.']);
    }

    ChamberCustomDate::create([
        'chamber_id' => $chamber->id,
        'date' => $request->date,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'slot_duration' => $request->slot_duration,
        'max_patients' => $request->max_patients,
        'is_active' => $request->boolean('is_active'),
    ]);

    return redirect()->back()->with('success', 'Custom date added successfully.');
}

    public function updateCustomDate(Request $request, ChamberCustomDate $customDate)
    {
        // Check if user owns the chamber
        if ($customDate->chamber->doctor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'required|integer|min:15|max:120',
            'max_patients' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $customDate->update($request->all());

        return redirect()->back()->with('success', 'Custom date updated successfully.');
    }

    // Get available slots for a chamber on specific date
    public function getAvailableSlots(Chamber $chamber, $date)
    {
        try {
            // Validate date format
            $parsedDate = Carbon::parse($date);

            if ($parsedDate->isPast() && !$parsedDate->isToday()) {
                return response()->json([
                    'slots' => [],
                    'fees' => $chamber->fees,
                    'message' => 'Cannot book appointments for past dates'
                ]);
            }

            $slots = $chamber->getAvailableSlots($date);

            // Remove already booked slots
            $bookedSlots = Appointment::where('chamber_id', $chamber->id)
                ->whereDate('appointment_date', $parsedDate->format('Y-m-d'))
                ->whereIn('status', ['confirmed', 'pending'])
                ->pluck('appointment_time')
                ->map(function ($time) {
                    return Carbon::parse($time)->format('H:i:s');
                })
                ->toArray();

            $availableSlots = array_filter($slots, function($slot) use ($bookedSlots) {
                return !in_array($slot['start'], $bookedSlots);
            });

            return response()->json([
                'slots' => array_values($availableSlots),
                'fees' => $chamber->fees,
                'date' => $parsedDate->format('Y-m-d'),
                'chamber' => $chamber->name
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'slots' => [],
                'fees' => $chamber->fees,
                'error' => 'Invalid date format or server error'
            ], 400);
        }
    }
public function getOnlineSlots(User $doctor, $date)
{
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
}
  private function createSampleCustomDates($chamber)
    {
        // Create sample dates for next 7 days
        for ($i = 1; $i <= 7; $i++) {
            $date = now()->addDays($i);

            ChamberCustomDate::create([
                'chamber_id' => $chamber->id,
                'date' => $date->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration' => 30,
                'max_patients' => 20,
                'is_active' => true,
            ]);
        }
    }
    public function destroyCustomDate(ChamberCustomDate $customDate)
{
    // Check if user owns the chamber
    if ($customDate->chamber->doctor_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $customDate->delete();

    return redirect()->back()->with('success', 'Custom date deleted successfully.');
}
// public function destroyCustomDate(ChamberCustomDate $customDate)
// {
//     // Check if user owns the chamber
//     if ($customDate->chamber->doctor_id !== auth()->id()) {
//         abort(403, 'Unauthorized action.');
//     }

//     $customDate->delete();

//     return redirect()->back()->with('success', 'Custom date deleted successfully.');
// }
}
