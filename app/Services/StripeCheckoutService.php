<?php

namespace App\Services;

use App\Models\PaymentSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StripeCheckoutService
{
    public function __construct(
        private readonly RegistrationPaymentGatewayResolver $gatewayResolver
    ) {
    }

    public function createDoctorRegistrationSession($tenant, $user, $package, array $data, $coupon = null, bool $api = false): string
    {
        $secret = $this->gatewayResolver->stripeSecret();
        if (!$secret) {
            throw new \Exception('Stripe is not configured. Please add Stripe credentials in admin settings.');
        }

        $amount = (float) ($data['total_amount'] ?? 0);
        if ($amount <= 0) {
            throw new \Exception('Stripe payment amount must be greater than zero.');
        }

        $localSessionId = (string) Str::uuid();
        $successPath = $api ? '/api/v1/stripe/success' : '/stripe/success';
        $cancelPath = $api ? '/api/v1/stripe/cancel' : '/stripe/cancel';

        $response = Http::asForm()
            ->withToken($secret)
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode' => 'payment',
                'success_url' => url($successPath) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url($cancelPath) . '?session_id={CHECKOUT_SESSION_ID}',
                'customer_email' => $user->email,
                'client_reference_id' => $tenant->id,
                'metadata[local_session_id]' => $localSessionId,
                'metadata[tenant_id]' => $tenant->id,
                'metadata[user_id]' => $user->id,
                'line_items[0][quantity]' => 1,
                'line_items[0][price_data][currency]' => 'usd',
                'line_items[0][price_data][unit_amount]' => (int) round($amount * 100),
                'line_items[0][price_data][product_data][name]' => 'Doctor Registration: ' . $package->name,
            ]);

        if (!$response->successful()) {
            $message = $response->json('error.message') ?: 'Stripe checkout session creation failed.';
            throw new \Exception($message);
        }

        $stripeSession = $response->json();

        PaymentSession::create([
            'session_id' => $localSessionId,
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'order_id' => $stripeSession['id'],
            'amount' => $amount,
            'coupon_id' => $coupon->id ?? null,
            'payment_gateway' => 'stripe',
            'expires_at' => now()->addHours(24),
        ]);

        return $stripeSession['url'];
    }

    public function retrieveSession(string $stripeSessionId): array
    {
        $secret = $this->gatewayResolver->stripeSecret();
        if (!$secret) {
            throw new \Exception('Stripe is not configured.');
        }

        $response = Http::withToken($secret)
            ->get('https://api.stripe.com/v1/checkout/sessions/' . urlencode($stripeSessionId));

        if (!$response->successful()) {
            $message = $response->json('error.message') ?: 'Unable to verify Stripe payment.';
            throw new \Exception($message);
        }

        return $response->json();
    }
}
