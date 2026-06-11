<?php

namespace Tests\Unit;

use App\Http\Requests\CouponStoreRequest;
use App\Http\Requests\CouponUpdateRequest;
use App\Http\Requests\Doctor\DoctorPostRequest;
use App\Http\Requests\Doctor\ServiceRequest;
use App\Models\Coupon;
use Tests\TestCase;

class FormRequestRulesTest extends TestCase
{
    public function test_coupon_store_request_normalizes_code_and_active_flag(): void
    {
        $request = TestableCouponStoreRequest::create('/coupons', 'POST', [
            'code' => ' save10 ',
            'is_active' => 'on',
        ]);

        $request->runPrepareForValidation();

        $this->assertSame('SAVE10', $request->input('code'));
        $this->assertTrue($request->boolean('is_active'));
    }

    public function test_coupon_store_request_defines_required_validation_contract(): void
    {
        $rules = (new CouponStoreRequest())->rules();

        $this->assertContains('required', $rules['code']);
        $this->assertContains('unique:coupons,code', $rules['code']);
        $this->assertContains('required', $rules['type']);
        $this->assertContains('in:fixed,percent', $rules['type']);
        $this->assertContains('after_or_equal:starts_at', $rules['expires_at']);
    }

    public function test_coupon_update_request_ignores_current_coupon_for_unique_rule(): void
    {
        $coupon = new Coupon();
        $coupon->id = 42;
        $request = TestableCouponUpdateRequest::create('/coupons/42', 'PUT', [
            'code' => ' renew ',
            'is_active' => '0',
        ]);
        $request->setRouteResolver(fn () => new class($coupon) {
            public function __construct(private Coupon $coupon)
            {
            }

            public function parameter(string $key): ?Coupon
            {
                return $key === 'coupon' ? $this->coupon : null;
            }
        });

        $request->runPrepareForValidation();
        $rules = $request->rules();

        $this->assertSame('RENEW', $request->input('code'));
        $this->assertFalse($request->boolean('is_active'));
        $this->assertContains('unique:coupons,code,42', $rules['code']);
    }

    public function test_doctor_service_request_rules_cover_service_payload(): void
    {
        $rules = (new ServiceRequest())->rules();

        $this->assertSame('required|string|max:255', $rules['title']);
        $this->assertSame('nullable|string', $rules['description']);
        $this->assertSame('nullable|array', $rules['features']);
        $this->assertSame('nullable|string|max:255', $rules['features.*']);
    }

    public function test_doctor_post_request_rules_cover_publishing_and_seo_payload(): void
    {
        $rules = (new DoctorPostRequest())->rules();

        $this->assertSame('required|string|max:255', $rules['title']);
        $this->assertSame('nullable|integer|min:1|max:120', $rules['read_minutes']);
        $this->assertSame('boolean', $rules['is_published']);
        $this->assertSame('nullable|string|max:255', $rules['meta_title']);
        $this->assertSame('nullable|array', $rules['related_post_ids']);
        $this->assertSame('nullable|integer|exists:doctor_posts,id', $rules['related_post_ids.*']);
    }
}

class TestableCouponStoreRequest extends CouponStoreRequest
{
    public function runPrepareForValidation(): void
    {
        $this->prepareForValidation();
    }
}

class TestableCouponUpdateRequest extends CouponUpdateRequest
{
    public function runPrepareForValidation(): void
    {
        $this->prepareForValidation();
    }
}
