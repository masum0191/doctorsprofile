<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'prescribed_date',
        'chief_complaint',
        'diagnosis',
        'instructions',
        'next_visit_date',
        'status',
        'medicines',
       'tests',
    ];

    protected $casts = [
        'prescribed_date' => 'date',
        'next_visit_date' => 'date',

        'medicines' => 'array',
       'tests' => 'array',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // 🔗 JOIN MEDICINES
    // public function medicines()
    // {
    //     return $this->belongsToMany(Medicine::class)
    //         ->withPivot(['dosage','frequency','duration','instruction'])
    //         ->withTimestamps();
    // }
    public function medicines()
    {
        return $this->belongsToMany(MedicineTemplate::class)->withTimestamps();
    }

    // 🔗 JOIN TESTS
    public function tests()
    {
        return $this->belongsToMany(Test::class)->withTimestamps();
    }
}
