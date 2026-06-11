<?php

namespace Tests\Unit;

use App\Models\Coupon;
use Tests\TestCase;

class CouponTest extends TestCase
{
    public function test_active_coupon_is_currently_valid_inside_date_and_usage_limits(): void
    {
        $coupon = new Coupon([
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
            'usage_limit' => 10,
            'used_count' => 3,
        ]);

        $this->assertTrue($coupon->isCurrentlyValid());
    }

    public function test_coupon_is_invalid_when_inactive_outside_dates_or_usage_exhausted(): void
    {
        $this->assertFalse((new Coupon(['is_active' => false]))->isCurrentlyValid());

        $this->assertFalse((new Coupon([
            'is_active' => true,
            'starts_at' => now()->addDay(),
        ]))->isCurrentlyValid());

        $this->assertFalse((new Coupon([
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]))->isCurrentlyValid());

        $this->assertFalse((new Coupon([
            'is_active' => true,
            'usage_limit' => 5,
            'used_count' => 5,
        ]))->isCurrentlyValid());
    }

    public function test_fixed_coupon_discount_respects_minimum_amount_and_never_exceeds_amount(): void
    {
        $coupon = new Coupon([
            'is_active' => true,
            'type' => 'fixed',
            'value' => 50,
            'min_amount' => 100,
        ]);

        $this->assertSame(0, $coupon->calculateDiscount(99));
        $this->assertSame(50.0, (float) $coupon->calculateDiscount(150));

        $noMinimumCoupon = new Coupon([
            'is_active' => true,
            'type' => 'fixed',
            'value' => 50,
            'min_amount' => 0,
        ]);

        $this->assertSame(40.0, (float) $noMinimumCoupon->calculateDiscount(40));
    }

    public function test_percent_coupon_discount_respects_max_discount(): void
    {
        $coupon = new Coupon([
            'is_active' => true,
            'type' => 'percent',
            'value' => 20,
            'min_amount' => 0,
            'max_discount' => 30,
        ]);

        $this->assertSame(20.0, (float) $coupon->calculateDiscount(100));
        $this->assertSame(30.0, (float) $coupon->calculateDiscount(500));
    }
}
