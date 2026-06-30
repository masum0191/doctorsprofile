<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentSession;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageUpgradeService
{
    public function options(string $tenantId): array
    {
        $packages = Package::where('is_visible', 1)
            ->orderBy('sort_order')
            ->get();

        $subscription = $this->activeSubscription($tenantId);
        $freePackage = Package::where('slug', 'free')->first();

        return [
            'packages' => $packages,
            'subscription' => $subscription,
            'pending_subscription' => $this->pendingSubscription($tenantId),
            'is_active' => $subscription
                && $subscription->ends_at
                && $subscription->ends_at->isFuture(),
            'current_package_id' => $subscription?->package_id,
            'has_used_free' => $freePackage
                ? Subscription::where('tenant_id', $tenantId)
                    ->where('package_id', $freePackage->id)
                    ->exists()
                : false,
        ];
    }

    public function quote(string $tenantId, int $packageId, string $billingCycle): array
    {
        $current = $this->activeSubscription($tenantId);
        $package = Package::findOrFail($packageId);
        $packagePrice = $billingCycle === 'yearly'
            ? (float) $package->price_yearly
            : (float) $package->price_monthly;

        $credit = $this->remainingCredit($current);
        $amountDue = max(0, $packagePrice - $credit);

        return [
            'current_subscription' => $current,
            'package' => $package,
            'billing_cycle' => $billingCycle,
            'package_price' => round($packagePrice, 2),
            'proration_credit' => round($credit, 2),
            'amount_due' => round($amountDue, 2),
        ];
    }

    public function requestUpgrade(
        ?User $doctor,
        string $tenantId,
        int $packageId,
        string $billingCycle,
        ?string $paymentMethod = null,
        bool $api = false
    ): array
    {
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            throw (new ModelNotFoundException())->setModel(Tenant::class, [$tenantId]);
        }

        $paymentMethod = $this->normalizePaymentMethod($paymentMethod);
        $quote = $this->quote($tenantId, $packageId, $billingCycle);
        $current = $quote['current_subscription'];
        $package = $quote['package'];

        $result = DB::connection('mysql')->transaction(function () use ($doctor, $tenant, $tenantId, $billingCycle, $current, $package, $quote, $paymentMethod) {
            $subscription = Subscription::create([
                'doctor_id' => $current?->doctor_id ?? $doctor?->id,
                'tenant_id' => $tenantId,
                'package_id' => $package->id,
                'billing_cycle' => $billingCycle,
                'starts_at' => now(),
                'ends_at' => $billingCycle === 'yearly'
                    ? now()->addYear()
                    : now()->addMonth(),
                'status' => 'pending',
            ]);

            $payment = $this->createTenantPayment($tenant, [
                'user_id' => $doctor?->id,
                'package_id' => $package->id,
                'amount' => $quote['amount_due'],
                'package_amount' => $quote['package_price'],
                'discount_amount' => $quote['proration_credit'],
                'payment_method' => $paymentMethod,
                'status' => $quote['amount_due'] <= 0 ? 'completed' : 'pending',
                'billing_cycle' => $billingCycle,
                'metadata' => [
                    'type' => 'package_upgrade',
                    'tenant_id' => $tenantId,
                    'current_subscription_id' => $current?->id,
                    'pending_subscription_id' => $subscription->id,
                    'proration_credit' => $quote['proration_credit'],
                ],
            ]);

            return [
                'subscription' => $subscription->load('package'),
                'payment' => $payment,
                'quote' => $quote,
                'payment_method' => $paymentMethod,
                'payment_url' => null,
                'payment_session' => null,
            ];
        });

        if (
            $quote['amount_due'] > 0
            && in_array($paymentMethod, ['stripe', 'sslcommerz'], true)
            && $doctor
        ) {
            $gateway = $this->initiateGatewayPayment(
                $tenant,
                $doctor,
                $package,
                $quote,
                $result['subscription'],
                $result['payment'],
                $paymentMethod,
                $api
            );

            $result['payment_url'] = $gateway['url'];
            $result['payment_session'] = $gateway['session'];
        }

        return $result;
    }

    public function isUpgradeSession(PaymentSession $session): bool
    {
        return data_get($session->metadata, 'type') === 'package_upgrade';
    }

    public function markGatewayPaymentCompleted(PaymentSession $session, string $transactionId, array $attributes = []): ?Payment
    {
        if (! $this->isUpgradeSession($session)) {
            return null;
        }

        $tenant = Tenant::find($session->tenant_id);
        if (! $tenant) {
            return null;
        }

        $payment = $this->runInTenantContext($tenant, function () use ($session, $transactionId, $attributes) {
            $payment = $this->tenantPaymentForSession($session);

            if ($payment) {
                $payment->update(array_merge([
                    'status' => 'completed',
                    'transaction_id' => $transactionId,
                    'payment_date' => now(),
                ], $attributes));
            }

            return $payment;
        });

        $session->update(['status' => 'completed']);

        return $payment;
    }

    public function markGatewayPaymentFailed(PaymentSession $session, string $status = 'failed'): ?Payment
    {
        if (! $this->isUpgradeSession($session)) {
            return null;
        }

        $tenant = Tenant::find($session->tenant_id);
        if (! $tenant) {
            return null;
        }

        $payment = $this->runInTenantContext($tenant, function () use ($session, $status) {
            $payment = $this->tenantPaymentForSession($session);

            if ($payment) {
                $payment->update(['status' => $status]);
            }

            return $payment;
        });

        $session->update(['status' => $status]);

        return $payment;
    }

    private function activeSubscription(string $tenantId): ?Subscription
    {
        return Subscription::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->latest()
            ->with('package')
            ->first();
    }

    private function pendingSubscription(string $tenantId): ?Subscription
    {
        return Subscription::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->latest()
            ->with('package')
            ->first();
    }

    private function remainingCredit(?Subscription $current): float
    {
        if (! $current || ! $current->starts_at || ! $current->ends_at || ! $current->package) {
            return 0;
        }

        $daysRemaining = now()->diffInDays($current->ends_at, false);
        $totalDays = $current->starts_at->diffInDays($current->ends_at);

        if ($daysRemaining <= 0 || $totalDays <= 0) {
            return 0;
        }

        return ((float) $current->package->price_monthly / $totalDays) * $daysRemaining;
    }

    private function createTenantPayment(Tenant $tenant, array $attributes): Payment
    {
        return $this->runInTenantContext($tenant, fn () => Payment::create($attributes));
    }

    private function initiateGatewayPayment(
        Tenant $tenant,
        User $doctor,
        Package $package,
        array $quote,
        Subscription $subscription,
        Payment $payment,
        string $paymentMethod,
        bool $api
    ): array {
        if ($paymentMethod === 'stripe') {
            return app(StripeCheckoutService::class)->createPackageUpgradeSession(
                $tenant,
                $doctor,
                $package,
                $quote,
                $subscription,
                $payment,
                $api
            );
        }

        $sslService = app(SSLCommerzService::class);
        $amountBdt = app(PricingService::class)->convertFromUsd((float) $quote['amount_due'], 'BDT');
        $transactionId = $sslService->generateTransactionId('UPGRADE');
        $postData = $sslService->createPackageUpgradeData($doctor, $package, $amountBdt, $transactionId);
        $postData['currency'] = 'BDT';
        $postData['success_url'] = url($api ? '/api/v1/sslcommerz/success' : '/sslcommerz/success');
        $postData['fail_url'] = url($api ? '/api/v1/sslcommerz/fail' : '/sslcommerz/fail');
        $postData['cancel_url'] = url($api ? '/api/v1/sslcommerz/cancel' : '/sslcommerz/cancel');
        $postData['ipn_url'] = url($api ? '/api/v1/sslcommerz/ipn' : '/sslcommerz/ipn');

        $session = PaymentSession::create([
            'session_id' => (string) Str::uuid(),
            'tenant_id' => $tenant->id,
            'user_id' => $doctor->id,
            'order_id' => $transactionId,
            'amount' => $amountBdt,
            'payment_gateway' => 'sslcommerz',
            'metadata' => $this->paymentSessionMetadata($subscription, $payment, $quote),
            'expires_at' => now()->addHours(24),
        ]);

        return [
            'url' => $sslService->initiatePayment($postData),
            'session' => $session,
        ];
    }

    private function tenantPaymentForSession(PaymentSession $session): ?Payment
    {
        $paymentId = data_get($session->metadata, 'tenant_payment_id');

        if ($paymentId) {
            return Payment::find($paymentId);
        }

        return Payment::where('package_id', data_get($session->metadata, 'package_id'))
            ->where('status', 'pending')
            ->latest()
            ->first();
    }

    public function paymentSessionMetadata(Subscription $subscription, Payment $payment, array $quote): array
    {
        return [
            'type' => 'package_upgrade',
            'subscription_id' => $subscription->id,
            'tenant_payment_id' => $payment->id,
            'package_id' => $subscription->package_id,
            'billing_cycle' => $subscription->billing_cycle,
            'amount_due_usd' => $quote['amount_due'],
            'package_price_usd' => $quote['package_price'],
            'proration_credit_usd' => $quote['proration_credit'],
        ];
    }

    private function normalizePaymentMethod(?string $paymentMethod): string
    {
        $paymentMethod = Str::of($paymentMethod ?? 'offline')
            ->trim()
            ->lower()
            ->replace(['-', ' '], '_')
            ->toString();

        return match ($paymentMethod) {
            'ssl', 'ssl_commerz', 'sslcommerce', 'ssl_commerce' => 'sslcommerz',
            'stripe', 'sslcommerz', 'bank_transfer', 'offline' => $paymentMethod,
            default => 'offline',
        };
    }

    private function runInTenantContext(Tenant $tenant, callable $callback): mixed
    {
        $wasInitialized = tenancy()->initialized;
        $previousTenant = tenancy()->tenant;
        $sameTenant = $wasInitialized
            && $previousTenant
            && (string) $previousTenant->getTenantKey() === (string) $tenant->getTenantKey();

        if (! $sameTenant) {
            tenancy()->initialize($tenant);
        }

        try {
            return $callback();
        } finally {
            if (! $wasInitialized) {
                tenancy()->end();
            } elseif (! $sameTenant && $previousTenant) {
                tenancy()->initialize($previousTenant);
            }
        }
    }
}
