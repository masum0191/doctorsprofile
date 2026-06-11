<?php

namespace Tests\Feature;

use App\Models\Domain;
use Tests\Concerns\InteractsWithCentralDatabase;
use Tests\TestCase;

class DomainAvailabilityTest extends TestCase
{
    use InteractsWithCentralDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpCentralDatabase();
        config([
            'app.base_domain' => 'doctorsprofile.xyz',
            'pricing.country_detection.enabled' => false,
            'pricing.rates.enabled' => false,
        ]);
    }

    public function test_subdomain_check_rejects_invalid_subdomain(): void
    {
        $this->postJson('http://doctorsprofile.xyz/check-subdomain', [
            'subdomain' => 'bad_value!',
        ])->assertUnprocessable()
            ->assertJsonPath('available', false);
    }

    public function test_subdomain_check_reports_available_and_taken_subdomains(): void
    {
        Domain::create([
            'domain' => 'taken.doctorsprofile.xyz',
            'type' => 'subdomain',
            'status' => 1,
        ]);

        $this->postJson('http://doctorsprofile.xyz/check-subdomain', [
            'subdomain' => 'open',
        ])->assertOk()
            ->assertJson([
                'available' => true,
                'subdomain' => 'open',
                'fullDomain' => 'open.doctorsprofile.xyz',
            ]);

        $this->postJson('http://doctorsprofile.xyz/check-subdomain', [
            'subdomain' => 'taken',
        ])->assertOk()
            ->assertJson([
                'available' => false,
                'subdomain' => 'taken',
                'fullDomain' => 'taken.doctorsprofile.xyz',
                'suggestion' => 'taken1',
            ]);
    }

    public function test_domain_check_reports_existing_domain_as_unavailable_without_external_lookup(): void
    {
        Domain::create([
            'domain' => 'drsmith.com',
            'type' => 'new',
            'status' => 1,
        ]);

        $this->postJson('http://doctorsprofile.xyz/check-domain', [
            'domain' => 'https://www.drsmith.com/path',
        ])->assertOk()
            ->assertJson([
                'available' => false,
                'domain' => 'drsmith.com',
                'domain_price_usd' => 14.99,
                'domain_price' => 14.99,
                'currency' => 'USD',
            ])
            ->assertJsonPath('suggestions.0', 'drsmithonline.com');
    }
}
