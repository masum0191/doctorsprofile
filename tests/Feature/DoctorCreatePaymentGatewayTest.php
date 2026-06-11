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
        $this->createPackage();

        $this->get('http://doctorsprofile.xyz/doctor/create')
            ->assertOk()
            ->assertSee('id="country"', false)
            ->assertSee('id="currency_code"', false)
            ->assertSee('id="sslcommerz-payment-card"', false)
            ->assertSee('id="stripe-payment-card"', false)
            ->assertSee('SSLCOMMERZ is available only for Bangladesh', false)
            ->assertSee('Only Stripe payment is available outside Bangladesh.', false);
    }

    public function test_international_create_page_hides_sslcommerz_and_shows_stripe(): void
    {
        $this->createPackage('standard-doctor-create-eur');

        $response = $this->get('http://doctorsprofile.xyz/doctor/create?currency=EUR')
            ->assertOk()
            ->assertSee('id="currency_code" value="EUR"', false);

        $html = $response->getContent();

        $this->assertMatchesRegularExpression(
            '/id="sslcommerz-payment-card"[\s\S]*?class="[^"]*\bhidden\b[^"]*"[\s\S]*?disabled/',
            $html
        );
        $this->assertMatchesRegularExpression(
            '/id="stripe-payment-card"[\s\S]*?class="(?![^"]*\bhidden\b)[^"]*"/',
            $html
        );
    }

    private function createPackage(string $slug = 'standard-doctor-create'): Package
    {
        return Package::create([
            'name' => 'Standard',
            'slug' => $slug,
            'description' => '<ul><li>Doctor profile</li></ul>',
            'price_monthly' => 10,
            'price_yearly' => 100,
            'storage_gb' => 5,
        ]);
    }
}
