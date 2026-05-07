<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorService extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';
    protected $table = 'doctor_services';

    protected $fillable = [
        'user_id', 'title', 'description', 'features', 'icon', 'badge', 'order_column'
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
