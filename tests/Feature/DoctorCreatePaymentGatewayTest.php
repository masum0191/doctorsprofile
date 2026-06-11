<?php

namespace Tests\Feature;

use App\Models\Package;
use Tests\Concerns\InteractsWithCentralDatabase;
use Tests\TestCase;

class DoctorCreatePaymentGatewayTest extends TestCase
{
    use InteractsWithCentralDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpCentralDatabase();
        config([
            'pricing.country_detection.enabled' => false,
            'pricing.rates.enabled' => false,
        ]);
    }

    public function test_doctor_create_page_contains_country_aware_payment_gateway_controls(): void
    {
        Package::create([
            'name' => 'Standard',
            'slug' => 'standard-doctor-create',
            'description' => '<ul><li>Doctor profile</li></ul>',
            'price_monthly' => 10,
            'price_yearly' => 100,
            'storage_gb' => 5,
        ]);

        $this->get('http://doctorsprofile.xyz/doctor/create')
            ->assertOk()
            ->assertSee('id="country"', false)
            ->assertSee('id="sslcommerz-payment-card"', false)
            ->assertSee('id="stripe-payment-card"', false)
            ->assertSee('SSLCOMMERZ is available only for Bangladesh', false)
            ->assertSee('Only Stripe payment is available outside Bangladesh.', false);
    }
}
