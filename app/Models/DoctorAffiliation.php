<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorAffiliation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','type','name','position','description','order_column'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
