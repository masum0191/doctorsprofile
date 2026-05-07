<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorSpecialty extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','name','description','patients_treated','icon','order_column'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
