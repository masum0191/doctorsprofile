<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\User;
use App\Services\RegistrationPaymentGatewayResolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
