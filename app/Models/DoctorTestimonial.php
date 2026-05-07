<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorTestimonial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','patient_name','photo','since','rating','verified','content','order_column'
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
