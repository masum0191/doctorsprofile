<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'tenant_id','channel','name','segment_id','template_id','subject','body','meta',
        'status','scheduled_at','started_at','completed_at','totals_json'
    ];

    protected $casts = [
        'meta' => 'array',
        'totals_json' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function segment() { return $this->belongsTo(Segment::class); }
    public function template() { return $this->belongsTo(MessageTemplate::class, 'template_id'); }
    public function recipients() { return $this->hasMany(CampaignRecipient::class); }
}
