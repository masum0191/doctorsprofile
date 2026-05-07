<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientEmr extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'chief_complaint',
        'history_of_present_illness',
        'comorbidities',
        'vitals',
        'examination',
        'diagnosis',
        'notes',
        'visit_date',
    ];

    protected $casts = [
        'comorbidities' => 'array',
        'vitals' => 'array',
        'visit_date' => 'date',
    ];
}
