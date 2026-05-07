<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'tagline',
        'email',
        'phone',
        'website',
        'address',
        'about',
        'footer_text',
        'logo',
        'favicon',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'tiktok_url',
        // sco 
        'meta_description',
        'keywords',
        'robots',
        'ogtitle',
        'ogdescription',
        'ogtype',
        'ogurl',
        'ogimage',
        'meta_title',
        'currency'
        
    ];
}
