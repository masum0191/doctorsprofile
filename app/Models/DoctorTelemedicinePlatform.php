<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorTelemedicinePlatform extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','name','icon','color','active','order_column'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
