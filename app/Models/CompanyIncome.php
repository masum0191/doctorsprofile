<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'type',
        'patient_name',
        'email',
        'phone',
        'amount',
        'payment_status',
        'company_profit',
        'doctor_profit',
        'commission_rate'
    ];

    // Relationship
    public function doctor()
    {
        return $this->belongsTo(User::class);
    }
}
