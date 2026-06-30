<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\InteractsWithCentralDatabase;
use Tests\TestCase;

class DoctorPackageUpgradeApiTest extends TestCase
{
    use InteractsWithCentralDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpCentralDatabase();

        config([
            'pricing.rates.enabled' => false,
            'services.stripe.secret' => 'sk_test_upgrade',
            'tenancy.bootstrappers' => [],
        ]);

        Carbon::setTestNow(Carbon::parse('2026-06-30 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_doctor_can_list_and_quote_package_upgrade(): void
    {
        [$doctor, $standard, $premium] = $this->createDoctorWithActiveSubscription();

        Sanctum::actingAs($doctor);

        $this->getJson('/api/v1/doctor/packages')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.current_package_id', $standard->id)
            ->assertJsonPath('data.current_subscription.package_name', 'Standard')
            ->assertJsonPath('data.packages.1.id', $premium->id);

        $this->postJson('/api/v1/doctor/packages/upgrade/quote', [
            'package_id' => $premium->id,
            'billing_cycle' => 'monthly',
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.package_price', 60)
            ->assertJsonPath('data.proration_credit', 15)
            ->assertJsonPath('data.amount_due', 45);
    }

    public function test_doctor_can_submit_package_upgrade_request(): void
    {
        [$doctor, $standard, $premium] = $this->createDoctorWithActiveSubscription();

        Sanctum::actingAs($doctor);

        $this->postJson('/api/v1/doctor/packages/upgrade', [
            'package_id' => $premium->id,
            'billing_cycle' => 'monthly',
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.subscription.status', 'pending')
            ->assertJsonPath('data.subscription.package_id', $premium->id)
            ->assertJsonPath('data.payment.amount', 45);

        $this->assertDatabaseHas('subscriptions', [
            'doctor_id' => $doctor->id,
            'tenant_id' => 'tenant-upgrade',
            'package_id' => $standard->id,
            'status' => 'active',
        ], 'mysql');

        $this->assertDatabaseHas('subscriptions', [
            'doctor_id' => $doctor->id,
            'tenant_id' => 'tenant-upgrade',
            'package_id' => $premium->id,
            'status' => 'pending',
            'billing_cycle' => 'monthly',
        ], 'mysql');

        $this->assertDatabaseHas('payments', [
            'user_id' => $doctor->id,
            'package_id' => $premium->id,
            'amount' => 45,
            'status' => 'pending',
            'billing_cycle' => 'monthly',
        ], 'mysql');
    }

    public function test_doctor_can_pay_package_upgrade_with_stripe_gateway(): void
    {
        [$doctor, $standard, $premium] = $this->createDoctorWithActiveSubscription();

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_upgrade_123',
                'url' => 'https://checkout.stripe.test/upgrade',
            ]),
            'https://api.stripe.com/v1/checkout/sessions/cs_upgrade_123' => Http::response([
                'id' => 'cs_upgrade_123',
                'payment_status' => 'paid',
            ]),
        ]);

        Sanctum::actingAs($doctor);

        $this->postJson('/api/v1/doctor/packages/upgrade', [
            'package_id' => $premium->id,
            'billing_cycle' => 'monthly',
            'payment_method' => 'stripe',
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.payment_method', 'stripe')
            ->assertJsonPath('data.payment_url', 'https://checkout.stripe.test/upgrade');

        $session = \App\Models\PaymentSession::where('order_id', 'cs_upgrade_123')->firstOrFail();

        $this->assertSame('package_upgrade', $session->metadata['type']);
        $this->assertSame($premium->id, $session->metadata['package_id']);
        $this->assertSame('45.00', (string) $session->amount);

        $this->assertDatabaseHas('payments', [
            'package_id' => $premium->id,
            'payment_method' => 'stripe',
            'amount' => 45,
            'status' => 'pending',
        ], 'mysql');

        $this->getJson('/api/v1/stripe/success?session_id=cs_upgrade_123')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Package upgrade payment successful. Upgrade request is waiting for superadmin approval.');

        $this->assertDatabaseHas('payments', [
            'package_id' => $premium->id,
            'payment_method' => 'stripe',
            'amount' => 45,
            'status' => 'completed',
            'transaction_id' => 'cs_upgrade_123',
        ], 'mysql');
        $this->assertSame('completed', $session->fresh()->status);
        $this->assertDatabaseHas('subscriptions', [
            'doctor_id' => $doctor->id,
            'tenant_id' => 'tenant-upgrade',
            'package_id' => $standard->id,
            'status' => 'active',
        ], 'mysql');
        $this->assertDatabaseHas('subscriptions', [
            'doctor_id' => $doctor->id,
            'tenant_id' => 'tenant-upgrade',
            'package_id' => $premium->id,
            'status' => 'pending',
        ], 'mysql');
    }

    private function createDoctorWithActiveSubscription(): array
    {
        DB::connection('mysql')->table('tenants')->insert([
            'id' => 'tenant-upgrade',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $doctor = User::create([
            'name' => 'Dr Upgrade',
            'email' => 'upgrade@example.com',
            'password' => 'secret',
            'role' => 'tenant',
            'tenant_id' => 'tenant-upgrade',
            'status' => 1,
        ]);

        $standard = Package::create([
            'name' => 'Standard',
            'slug' => 'standard',
            'price_monthly' => 30,
            'price_yearly' => 300,
            'storage_gb' => 5,
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $premium = Package::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'price_monthly' => 60,
            'price_yearly' => 600,
            'storage_gb' => 20,
            'is_visible' => true,
            'sort_order' => 2,
        ]);

        Subscription::create([
            'doctor_id' => $doctor->id,
            'tenant_id' => 'tenant-upgrade',
            'package_id' => $standard->id,
            'billing_cycle' => 'monthly',
            'starts_at' => now()->subDays(15),
            'ends_at' => now()->addDays(15),
            'status' => 'active',
        ]);

        return [$doctor, $standard, $premium];
    }
}
