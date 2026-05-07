<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChamberCustomDate extends Model
{
    protected $fillable = [
        'chamber_id', 'date', 'start_time', 'end_time',
        'slot_duration', 'max_patients', 'is_active'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean'
    ];

    public function chamber()
    {
        return $this->belongsTo(Chamber::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'chamber_id', 'chamber_id')
                    ->whereDate('appointment_date', $this->date);
    }

    public function getAvailableSlotsCount()
    {
        $totalSlots = count($this->chamber->generateTimeSlots(
            $this->start_time,
            $this->end_time,
            $this->slot_duration
        ));

        $bookedSlots = $this->appointments()->count();

        return $totalSlots - $bookedSlots;
    }
}
