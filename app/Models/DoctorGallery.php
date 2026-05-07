<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorGallery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','title','category','image_url','caption','order_column'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
