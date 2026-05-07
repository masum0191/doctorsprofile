<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorCertification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','title','organization','year','status','description','order_column'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
