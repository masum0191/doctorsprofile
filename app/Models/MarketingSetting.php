<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingSetting extends Model
{
    protected $table = 'marketing_settings';

    protected $fillable = [
        'tenant_id','email_provider','email_from_name','email_from_address',
        'smtp_host','smtp_port','smtp_user','smtp_pass','smtp_encryption',
        'whatsapp_provider','whatsapp_token','whatsapp_phone_number_id','whatsapp_business_account_id',
        'sending_limits'
    ];

    protected $casts = [
        'sending_limits' => 'array',
    ];
}
