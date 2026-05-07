<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'image',
        'video_url',
        'click_url',
        'button_text',
        'target',
        'status',
        'order',
        'start_at',
        'end_at',
        'created_by'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'status'   => 'boolean',
    ];
}
