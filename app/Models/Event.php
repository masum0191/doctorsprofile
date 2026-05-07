<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'sub_title',
        'image_gallery',
        'video_url',
        'description',
        'publish_date',
        'status',
        'venue',
    ];

    protected $casts = [
        'image_gallery' => 'array',
        'publish_date'  => 'date',
        'status'        => 'boolean',
    ];
}
