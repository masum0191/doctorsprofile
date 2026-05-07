<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorExperience extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','title','organization','start_year','end_year','description','order_column'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
