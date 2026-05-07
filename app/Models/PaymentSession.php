<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSession extends Model
{
    protected $fillable = [
        'session_id',
        'tenant_id',
        'user_id',
        'order_id',
        'amount',
        'coupon_id',
        'payment_gateway',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }
}
