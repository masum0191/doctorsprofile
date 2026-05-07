<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Chamber extends Model
{
    protected $fillable = [
        'doctor_id', 'name', 'phone', 'email', 'website', 'image', 'address', 'city', 'fees', 'type', 'schedule', 'latitude', 'longitude', 'is_active'
    ];

    protected $casts = [
        'schedule' => 'array',
        'fees' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function customDates()
    {
        return $this->hasMany(ChamberCustomDate::class);
    }

    // Get available time slots for a specific date
    public function getAvailableSlots($date)
    {
        // Parse the date properly
        $date = Carbon::parse($date);

        // Get chamber schedule for this date
        $schedule = $this->getScheduleForDate($date);

        if (!$schedule || !$schedule->is_active) {
            return [];
        }

        $slots = [];
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $slotDuration = $schedule->slot_duration ?? 30; // minutes

        // Generate time slots
        $currentTime = $startTime->copy();

        while ($currentTime < $endTime) {
            $slotEnd = $currentTime->copy()->addMinutes($slotDuration);

            if ($slotEnd <= $endTime) {
                $slots[] = [
                    'start' => $currentTime->format('H:i:s'),
                    'end' => $slotEnd->format('H:i:s'),
                    'display' => $currentTime->format('g:i A') . ' - ' . $slotEnd->format('g:i A')
                ];
            }

            // ✅ FIX: Use numeric value instead of string
            $currentTime->addMinutes($slotDuration);
        }

        return $slots;
    }

    /**
     * Get schedule for a specific date
     */
    private function getScheduleForDate1(Carbon $date)
    {
        // First check for custom dates
        $customDate = ChamberCustomDate::where('chamber_id', $this->id)
            ->where('date', $date->format('Y-m-d'))
            ->where('is_active', true)
            ->first();

        if ($customDate) {
            return $customDate;
        }

        // Fall back to regular schedule based on day of week
        $dayOfWeek = strtolower($date->englishDayOfWeek);

        return (object) [
            'start_time' => $this->{$dayOfWeek . '_start_time'} ?? '09:00',
            'end_time' => $this->{$dayOfWeek . '_end_time'} ?? '17:00',
            'slot_duration' => $this->slot_duration ?? 30,
            'is_active' => $this->{$dayOfWeek . '_active'} ?? true,
        ];
    }

    private function getScheduleForDate(Carbon $date)
    {
        // First check for custom dates
        $customDate = ChamberCustomDate::where('chamber_id', $this->id)
            ->where('date', $date->format('Y-m-d'))
            ->where('is_active', true)
            ->first();

        if ($customDate) {
            return $customDate;
        }

        // Fall back to regular schedule based on day of week
        $dayOfWeek = strtolower($date->englishDayOfWeek);

        return (object) [
            'start_time' => $this->{$dayOfWeek . '_start_time'} ?? '09:00',
            'end_time' => $this->{$dayOfWeek . '_end_time'} ?? '17:00',
            'slot_duration' => $this->slot_duration ?? 30,
            'is_active' => $this->{$dayOfWeek . '_active'} ?? true,
        ];
    }

    private function getCustomDateSlots($date)
    {
        $customDate = $this->customDates()->where('date', $date)->first();

        if (!$customDate || !$customDate->is_active) {
            return [];
        }

        return $this->generateTimeSlots($customDate->start_time, $customDate->end_time, $customDate->slot_duration);
    }

    private function generateTimeSlots($startTime, $endTime, $slotDuration)
    {
        $slots = [];
        $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
        $end = \Carbon\Carbon::createFromFormat('H:i', $endTime);

        while ($start->lessThan($end)) {
            $slotEnd = $start->copy()->addMinutes($slotDuration);

            if ($slotEnd->lessThanOrEqualTo($end)) {
                $slots[] = [
                    'start' => $start->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'display' => $start->format('h:i A') . ' - ' . $slotEnd->format('h:i A')
                ];
            }

            $start->addMinutes($slotDuration);
        }

        return $slots;
    }

}
