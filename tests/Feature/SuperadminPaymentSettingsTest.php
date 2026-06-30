<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\User;
use App\Services\RegistrationPaymentGatewayResolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\InteractsWithCentralDatabase;
use Tests\TestCase;

class SuperadminPaymentSettingsTest extends TestCase
{
    use InteractsWithCentralDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpCentralDatabase();
        $this->createCompanySettingsTable();
    }

    public function test_superadmin_can_open_payment_gateway_settings_page(): void
    {
        $this->actingAs($this->adminUser())
            ->get(route('superadmin.payment.settings'))
            ->assertOk()
            ->assertSee('Payment Gateway Settings')
            ->assertSee('name="stripe_key"', false)
            ->assertSee('name="stripe_secret"', false);
    }

    public function test_superadmin_can_save_stripe_credentials(): void
    {
        $this->actingAs($this->adminUser())
            ->post(route('superadmin.payment.settings.update'), [
                'sslcommerz_enabled' => '1',
                'sslcommerz_store_id' => 'store_test',
                'sslcommerz_store_password' => 'ssl_secret_test',
                'sslcommerz_test_mode' => '1',
                'stripe_enabled' => '1',
                'stripe_key' => 'pk_test_admin',
                'stripe_secret' => 'sk_test_admin',
                'country_gateways' => "Bangladesh=sslcommerz\nIndia=stripe",
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $paymentGateway = CompanySetting::firstOrFail()->payment_gateway;

        $this->assertTrue($paymentGateway['stripe']['enabled']);
        $this->assertSame('pk_test_admin', $paymentGateway['stripe']['key']);
        $this->assertSame('sk_test_admin', $paymentGateway['stripe']['secret']);
        $this->assertSame('stripe', $paymentGateway['country_gateways']['India']);
        $this->assertSame('sk_test_admin', app(RegistrationPaymentGatewayResolver::class)->stripeSecret());
    }

    public function test_stripe_secret_is_required_when_enabling_stripe_for_first_time(): void
    {
        $this->actingAs($this->adminUser())
            ->from(route('superadmin.payment.settings'))
            ->post(route('superadmin.payment.settings.update'), [
                'stripe_enabled' => '1',
                'stripe_key' => 'pk_test_admin',
            ])
            ->assertRedirect(route('superadmin.payment.settings'))
            ->assertSessionHasErrors('stripe_secret');
    }

    public function test_existing_stripe_secret_is_preserved_when_field_is_left_blank(): void
    {
        CompanySetting::create([
            'payment_gateway' => [
                'stripe' => [
                    'enabled' => true,
                    'key' => 'pk_test_old',
                    'secret' => 'sk_test_existing',
                ],
            ],
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('superadmin.payment.settings.update'), [
                'stripe_enabled' => '1',
                'stripe_key' => 'pk_test_new',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $paymentGateway = CompanySetting::firstOrFail()->payment_gateway;

        $this->assertSame('pk_test_new', $paymentGateway['stripe']['key']);
        $this->assertSame('sk_test_existing', $paymentGateway['stripe']['secret']);
    }

    public function test_superadmin_can_save_payment_gateway_settings_via_api(): void
    {
        Sanctum::actingAs($this->adminUser());

        $this->postJson('/api/v1/payment-gateways', [
            'sslcommerz' => [
                'enabled' => true,
                'store_id' => 'store_api',
                'store_password' => 'ssl_secret_api',
                'test_mode' => true,
            ],
            'stripe' => [
                'enabled' => true,
                'key' => 'pk_test_api',
                'secret' => 'sk_test_api',
            ],
            'country_gateways' => [
                'Bangladesh' => 'sslcommerz',
                'India' => 'stripe',
            ],
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.sslcommerz.store_password_configured', true)
            ->assertJsonPath('data.stripe.secret_configured', true)
            ->assertJsonMissing(['secret' => 'sk_test_api'])
            ->assertJsonMissing(['store_password' => 'ssl_secret_api']);

        $paymentGateway = CompanySetting::firstOrFail()->payment_gateway;

        $this->assertSame('store_api', $paymentGateway['sslcommerz']['store_id']);
        $this->assertSame('ssl_secret_api', $paymentGateway['sslcommerz']['store_password']);
        $this->assertSame('pk_test_api', $paymentGateway['stripe']['key']);
        $this->assertSame('sk_test_api', $paymentGateway['stripe']['secret']);
        $this->assertSame('stripe', $paymentGateway['country_gateways']['India']);
    }

    public function test_payment_gateway_api_preserves_existing_secrets_and_requires_admin_role(): void
    {
        CompanySetting::create([
            'payment_gateway' => [
                'sslcommerz' => [
                    'enabled' => true,
                    'store_id' => 'store_existing',
                    'store_password' => 'ssl_secret_existing',
                    'secret' => 'ssl_secret_existing',
                ],
                'stripe' => [
                    'enabled' => true,
                    'key' => 'pk_test_old',
                    'secret' => 'sk_test_existing',
                ],
            ],
        ]);

        Sanctum::actingAs($this->adminUser());

        $this->postJson('/api/v1/payment-gateway-settings', [
            'sslcommerz_enabled' => '1',
            'sslcommerz_store_id' => 'store_new',
            'stripe_enabled' => '1',
            'stripe_key' => 'pk_test_new',
        ])->assertOk()
            ->assertJsonPath('data.sslcommerz.store_password_configured', true)
            ->assertJsonPath('data.stripe.secret_configured', true);

        $paymentGateway = CompanySetting::firstOrFail()->payment_gateway;

        $this->assertSame('ssl_secret_existing', $paymentGateway['sslcommerz']['store_password']);
        $this->assertSame('sk_test_existing', $paymentGateway['stripe']['secret']);

        Sanctum::actingAs(User::create([
            'name' => 'Tenant',
            'email' => 'tenant@example.test',
            'password' => 'password',
            'role' => 'tenant',
        ]));

        $this->getJson('/api/v1/payment-gateways')->assertForbidden();
    }

    private function adminUser(): User
    {
        return User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }

    private function createCompanySettingsTable(): void
    {
        Schema::connection('mysql')->dropIfExists('company_settings');
        Schema::connection('mysql')->create('company_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('company_name')->nullable();
            $table->json('payment_gateway')->nullable();
            $table->timestamps();
        });
    }
}
