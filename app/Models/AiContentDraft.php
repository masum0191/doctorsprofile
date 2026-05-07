<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiContentDraft extends Model
{
    protected $fillable = ['user_id','target','payload','status','source','notes'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
