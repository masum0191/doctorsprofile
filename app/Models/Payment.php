<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'package_id',
        'coupon_id',
        'amount',
        'package_amount',
        'domain_amount',
        'discount_amount',
        'payment_method',
        'status',
        'transaction_id',
        'card_last_four',
        'card_type',
        'billing_cycle',
        'payment_date',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'package_amount' => 'decimal:2',
        'domain_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'metadata' => 'array'
    ];

    /**
     * Get the tenant that owns the payment.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package that owns the payment.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the coupon that owns the payment.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Scope a query to only include completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get the payment status as a human-readable string.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get the payment method as a human-readable string.
     */
    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'credit_card' => 'Credit/Debit Card',
            'digital_wallet' => 'Digital Wallet',
            'bank_transfer' => 'Bank Transfer',
            default => ucfirst(str_replace('_', ' ', $this->payment_method))
        };
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }
}
