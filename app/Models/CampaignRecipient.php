<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignRecipient extends Model
{
    protected $fillable = [
        'campaign_id','contact_id','status','error_message',
        'queued_at','sent_at','delivered_at','opened_at','provider_message_id'
    ];

    protected $casts = [
        'queued_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
    ];

    public function campaign() { return $this->belongsTo(Campaign::class); }
    public function contact() { return $this->belongsTo(Contact::class); }
}
