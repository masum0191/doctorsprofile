<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorEducation extends Model
{
    use SoftDeletes;
    protected $connection = 'tenant';
    protected $table = 'doctor_educations';

    protected $fillable = [
        'user_id','degree','institution','start_year','end_year','city','country','description','order_column'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}


