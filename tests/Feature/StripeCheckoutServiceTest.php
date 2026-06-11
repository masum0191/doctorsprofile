<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\PaymentSession;
use App\Models\User;
use App\Services\StripeCheckoutService;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\InteractsWithCentralDatabase;
use Tests\TestCase;

class StripeCheckoutServiceTest extends TestCase
{
    use InteractsWithCentralDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpCentralDatabase();
        config([
            'services.stripe.secret' => 'sk_test_local',
            'app.url' => 'https://doctorsprofile.xyz',
        ]);
    }

    public function test_it_creates_stripe_checkout_session_and_persists_local_payment_session(): void
    {
        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.test/session',
            ]),
        ]);

        $tenant = (object) ['id' => 'tenant-stripe'];
        $user = User::create([
            'name' => 'Dr Stripe',
            'email' => 'stripe@example.com',
            'password' => 'secret',
        ]);
        $package = Package::create([
            'name' => 'Standard',
            'slug' => 'standard',
            'price_monthly' => 10,
            'price_yearly' => 100,
            'storage_gb' => 5,
        ]);

        $url = app(StripeCheckoutService::class)->createDoctorRegistrationSession(
            $tenant,
            $user,
            $package,
            ['total_amount' => 19.99],
            null,
            true
        );

        $this->assertSame('https://checkout.stripe.test/session', $url);
        $this->assertDatabaseHas('payment_sessions', [
            'tenant_id' => 'tenant-stripe',
            'user_id' => $user->id,
            'order_id' => 'cs_test_123',
            'amount' => 19.99,
            'payment_gateway' => 'stripe',
        ], 'mysql');

        Http::assertSent(function ($request) use ($user, $tenant) {
            return $request->url() === 'https://api.stripe.com/v1/checkout/sessions'
                && $request['customer_email'] === $user->email
                && $request['client_reference_id'] === $tenant->id
                && $request['line_items[0][price_data][unit_amount]'] === 1999
                && str_contains($request['success_url'], '/api/v1/stripe/success');
        });
    }

    public function test_it_throws_when_stripe_returns_an_error(): void
    {
        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'error' => ['message' => 'Invalid API key'],
            ], 401),
        ]);

        $this->expectExceptionMessage('Invalid API key');

        app(StripeCheckoutService::class)->createDoctorRegistrationSession(
            (object) ['id' => 'tenant-fail'],
            User::create(['name' => 'Dr Fail', 'email' => 'fail@example.com']),
            Package::create([
                'name' => 'Standard',
                'slug' => 'standard-fail',
                'price_monthly' => 10,
                'price_yearly' => 100,
                'storage_gb' => 5,
            ]),
            ['total_amount' => 10]
        );
    }

    public function test_it_retrieves_stripe_checkout_session(): void
    {
        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions/cs_test_123' => Http::response([
                'id' => 'cs_test_123',
                'payment_status' => 'paid',
            ]),
        ]);

        $session = app(StripeCheckoutService::class)->retrieveSession('cs_test_123');

        $this->assertSame('cs_test_123', $session['id']);
        $this->assertSame('paid', $session['payment_status']);
    }
}
