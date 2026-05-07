<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'tenant_id','name','email','phone','whatsapp','bmdc_no','city','area','address','specialty',
        'tags_json','opt_in_email','opt_in_whatsapp','do_not_contact',
        'email_verified_at','whatsapp_verified_at','source','notes','last_contacted_at','status',
    ];

    protected $casts = [
        'tags_json' => 'array',
        'opt_in_email' => 'boolean',
        'opt_in_whatsapp' => 'boolean',
        'do_not_contact' => 'boolean',
        'email_verified_at' => 'datetime',
        'whatsapp_verified_at' => 'datetime',
        'last_contacted_at' => 'datetime',
    ];

    public function unsubscribes() {
        return $this->hasMany(Unsubscribe::class);
    }
}
