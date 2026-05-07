<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSnapshot extends Model
{
    protected $fillable = ['user_id','label','data','created_by','published_at'];

    protected $casts = [
        'data' => 'array',
        'published_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
