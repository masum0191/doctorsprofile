<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'title',
        'message',
        'is_read'
    ];
}

