<?php

namespace Tests\Feature;

use App\Models\Coupon;
use Tests\Concerns\InteractsWithCentralDatabase;
use Tests\TestCase;

class CouponApiTest extends TestCase
{
    use InteractsWithCentralDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpCentralDatabase();
    }

    public function test_available_coupons_returns_only_currently_usable_coupons_for_amount(): void
    {
        Coupon::create([
            'code' => 'SAVE20',
            'description' => null,
            'note' => 'Twenty percent off',
            'type' => 'percent',
            'value' => 20,
            'min_amount' => 100,
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
            'usage_limit' => 10,
            'used_count' => 1,
        ]);
        Coupon::create([
            'code' => 'INACTIVE',
            'type' => 'fixed',
            'value' => 10,
            'is_active' => false,
        ]);
        Coupon::create([
            'code' => 'EXPIRED',
            'type' => 'fixed',
            'value' => 10,
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);
        Coupon::create([
            'code' => 'TOOEXPENSIVE',
            'type' => 'fixed',
            'value' => 10,
            'min_amount' => 500,
            'is_active' => true,
        ]);

        $response = $this->getJson('http://doctorsprofile.xyz/api/coupons/available?amount=150');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'code' => 'SAVE20',
                'description' => 'Twenty percent off',
            ]);
    }

    public function test_validate_coupon_returns_discount_for_valid_coupon_code(): void
    {
        Coupon::create([
            'code' => 'SAVE20',
            'type' => 'percent',
            'value' => 20,
            'max_discount' => 25,
            'min_amount' => 50,
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->postJson('http://doctorsprofile.xyz/api/coupons/validate', [
            'code' => ' save20 ',
            'amount' => 200,
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'message' => 'Coupon applied successfully!',
                'discount_amount' => '25.00',
            ])
            ->assertJsonPath('coupon.code', 'SAVE20');
    }

    public function test_validate_coupon_rejects_unknown_expired_and_below_minimum_codes(): void
    {
        Coupon::create([
            'code' => 'EXPIRED',
            'type' => 'fixed',
            'value' => 10,
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);
        Coupon::create([
            'code' => 'MINIMUM',
            'type' => 'fixed',
            'value' => 10,
            'min_amount' => 200,
            'is_active' => true,
        ]);

        $this->postJson('http://doctorsprofile.xyz/api/coupons/validate', [
            'code' => 'missing',
            'amount' => 100,
        ])->assertOk()->assertJson([
            'valid' => false,
            'message' => 'Invalid coupon code',
        ]);

        $this->postJson('http://doctorsprofile.xyz/api/coupons/validate', [
            'code' => 'EXPIRED',
            'amount' => 100,
        ])->assertOk()->assertJson([
            'valid' => false,
            'message' => 'Coupon is no longer valid',
        ]);

        $this->postJson('http://doctorsprofile.xyz/api/coupons/validate', [
            'code' => 'MINIMUM',
            'amount' => 100,
        ])->assertOk()->assertJson([
            'valid' => false,
            'message' => 'Minimum purchase amount not met',
        ]);
    }
}
