<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorFaq extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','question','answer','order_column'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
