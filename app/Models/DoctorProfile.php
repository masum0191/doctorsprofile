<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id','headline','subheadline','hero_image','tagline',
        'about_short','about_long','years_experience','patients_count',
        'satisfaction_rate','accepts_virtual_visits','accepts_insurance',
        'sections','meta','published_at'
    ];

    protected $casts = [
        'accepts_virtual_visits' => 'boolean',
        'accepts_insurance'      => 'boolean',
        'sections'               => 'array',
        'meta'                   => 'array',
        'published_at'           => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
