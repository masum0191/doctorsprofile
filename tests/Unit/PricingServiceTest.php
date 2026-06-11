<?php

namespace Tests\Unit;

use App\Services\PricingService;
use Illuminate\Http\Request;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    private PricingService $pricingService;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'pricing.country_detection.enabled' => false,
            'pricing.rates.enabled' => false,
        ]);

        $this->pricingService = new PricingService();
    }

    public function test_supported_currency_query_override_wins_over_country_detection(): void
    {
        $request = Request::create('/packages', 'GET', ['currency' => 'bdt']);

        $this->assertSame('BDT', $this->pricingService->detectCurrencyCode($request, 'US'));
    }

    public function test_unsupported_currency_query_falls_back_to_detected_country(): void
    {
        $request = Request::create('/packages', 'GET', ['currency' => 'invalid'], [], [], [
            'HTTP_X_COUNTRY_CODE' => 'IN',
        ]);

        $this->assertSame('IN', $this->pricingService->detectCountry($request));
        $this->assertSame('INR', $this->pricingService->detectCurrencyCode($request));
    }

    public function test_context_for_request_contains_static_currency_metadata(): void
    {
        $request = Request::create('/packages', 'GET', [], [], [], [
            'HTTP_CF_IPCOUNTRY' => 'BD',
        ]);

        $context = $this->pricingService->contextForRequest($request);

        $this->assertSame('USD', $context['base_currency']);
        $this->assertSame('BD', $context['country']);
        $this->assertSame('BDT', $context['currency_code']);
        $this->assertSame('Bangladeshi Taka', $context['currency_name']);
        $this->assertSame(120.0, $context['exchange_rate']);
        $this->assertArrayHasKey('.com', $context['domain_prices_usd']);
    }

    public function test_convert_from_usd_uses_static_rates_and_unknown_currency_falls_back_to_usd(): void
    {
        $this->assertSame(1200.0, $this->pricingService->convertFromUsd(10.0, 'BDT'));
        $this->assertSame(10.0, $this->pricingService->convertFromUsd(10.0, 'UNKNOWN'));
    }

    public function test_domain_price_only_applies_to_new_domains_and_normalizes_extension(): void
    {
        $this->assertSame(16.99, $this->pricingService->domainPriceUsd('new', 'net'));
        $this->assertSame(14.99, $this->pricingService->domainPriceUsd('new', 'unknown'));
        $this->assertSame(0.0, $this->pricingService->domainPriceUsd('existing', '.com'));
    }

    public function test_package_payload_includes_usd_and_converted_prices(): void
    {
        $package = (object) [
            'price_monthly' => '12.50',
            'price_yearly' => 100,
        ];

        $payload = $this->pricingService->packagePayload($package, 'BDT');

        $this->assertSame(12.5, $payload['price_monthly_usd']);
        $this->assertSame(100.0, $payload['price_yearly_usd']);
        $this->assertSame(1500.0, $payload['price_monthly']);
        $this->assertSame(12000.0, $payload['price_yearly']);
    }
}
