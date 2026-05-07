<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $connection = 'tenant';
    protected $casts = [
        'extra_data' => 'array', // <= important
        'online_schedule' => 'array',
    ];
    protected $fillable = [
        'site_name',
        'tagline',
        'email',
        'site_logo',
        'address',
        'phone',
        'footer_text',

        'meta_description',
        'keywords',
        'robots',
        'ogtitle',
        'ogdescription',
        'ogtype',
        'ogurl',
        'ogimage',
        'watermark',
        'facebook_url',
        'youtube_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'whatsapp_number',
        'worktime',
        'latitude',
        'longitude',
        'office_name',
        'address_line1',
        'address_line2',
        'district',
        'post_code',
        'helpline_number',
        'support_email',
        'general_email',
        'fax_number',
        'emergency_number',
        'extra_data',
        'template',
        'online_schedule'
    ];

    public static function getSetting($key)
    {
        $setting = self::first();
        return $setting ? $setting->$key : null;
    }
}
