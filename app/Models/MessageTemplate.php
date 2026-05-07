<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    protected $fillable = [
        'tenant_id','channel','name','subject','body','meta','variables','is_active'
    ];

    protected $casts = [
        'meta' => 'array',
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
}
