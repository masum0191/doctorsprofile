<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
     protected $connection = 'mysql'; // central DB

    protected $fillable = [
        'doctor_id',
        'tenant_id',
        'package_id',
        'billing_cycle',
        'starts_at',
        'ends_at',
        'status'
    ];
protected $casts = [
    'starts_at' => 'datetime',
    'ends_at'   => 'datetime',
];
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_id');
}

}
