<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\PackageUpgradeService;
use App\Services\PricingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DoctorPackageUpgradeController extends Controller
{
    public function index(Request $request, PackageUpgradeService $upgrades, PricingService $pricing)
    {
        $tenantId = $this->tenantId($request);

        if (! $tenantId) {
            return $this->tenantNotResolvedResponse();
        }

        $options = $upgrades->options($tenantId);
        $pricingContext = $pricing->contextForRequest($request, $request->user()?->country);

        return response()->json([
            'success' => true,
            'pricing' => $pricingContext,
            'data' => [
                'packages' => $options['packages']->map(fn (Package $package) => $this->packagePayload($package, $pricing, $pricingContext)),
                'current_subscription' => $this->subscriptionPayload($options['subscription']),
                'pending_subscription' => $this->subscriptionPayload($options['pending_subscription']),
                'is_active' => $options['is_active'],
                'current_package_id' => $options['current_package_id'],
                'has_used_free' => $options['has_used_free'],
            ],
        ]);
    }

    public function quote(Request $request, PackageUpgradeService $upgrades, PricingService $pricing)
    {
        $validated = $request->validate($this->rules());
        $tenantId = $this->tenantId($request);

        if (! $tenantId) {
            return $this->tenantNotResolvedResponse();
        }

        $quote = $upgrades->quote($tenantId, (int) $validated['package_id'], $validated['billing_cycle']);
        $pricingContext = $pricing->contextForRequest($request, $request->user()?->country);

        return response()->json([
            'success' => true,
            'pricing' => $pricingContext,
            'data' => $this->quotePayload($quote, $pricing, $pricingContext),
        ]);
    }

    public function store(Request $request, PackageUpgradeService $upgrades, PricingService $pricing)
    {
        $validated = $request->validate($this->rules());
        $tenantId = $this->tenantId($request);

        if (! $tenantId) {
            return $this->tenantNotResolvedResponse();
        }

        if (! Tenant::find($tenantId)) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        try {
            $result = $upgrades->requestUpgrade(
                $request->user(),
                $tenantId,
                (int) $validated['package_id'],
                $validated['billing_cycle'],
                $validated['payment_method'] ?? null,
                true
            );
            $pricingContext = $pricing->contextForRequest($request, $request->user()?->country);

            return response()->json([
                'success' => true,
                'message' => 'Package upgrade request submitted. New package features will apply after superadmin approval.',
                'data' => [
                    'subscription' => $this->subscriptionPayload($result['subscription']),
                    'payment' => $this->paymentPayload($result['payment']),
                    'payment_method' => $result['payment_method'],
                    'payment_url' => $result['payment_url'],
                    'payment_session_id' => $result['payment_session']?->session_id,
                    'quote' => $this->quotePayload($result['quote'], $pricing, $pricingContext),
                ],
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Package or tenant not found.',
            ], 404);
        } catch (\Throwable $e) {
            Log::error('Doctor package upgrade API failed', [
                'user_id' => $request->user()?->id,
                'tenant_id' => $tenantId,
                'package_id' => $validated['package_id'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Package upgrade request failed.',
            ], 500);
        }
    }

    private function rules(): array
    {
        return [
            'package_id' => ['required', 'integer', Rule::exists('mysql.packages', 'id')],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'payment_method' => ['nullable', Rule::in(['stripe', 'sslcommerz', 'ssl_commerce', 'bank_transfer', 'offline'])],
        ];
    }

    private function tenantId(Request $request): ?string
    {
        return $request->user()?->tenant_id
            ? (string) $request->user()->tenant_id
            : null;
    }

    private function tenantNotResolvedResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved for authenticated doctor.',
        ], 422);
    }

    private function packagePayload(Package $package, PricingService $pricing, array $pricingContext): array
    {
        $pricePayload = $pricing->packagePayload($package, $pricingContext['currency_code']);

        return [
            'id' => $package->id,
            'name' => $package->name,
            'slug' => $package->slug,
            'description' => $package->description,
            'base_currency' => $pricingContext['base_currency'],
            'display_currency' => $pricingContext['currency_code'],
            'display_currency_symbol' => $pricingContext['currency_symbol'],
            'price_monthly_usd' => $pricePayload['price_monthly_usd'],
            'price_yearly_usd' => $pricePayload['price_yearly_usd'],
            'price_monthly' => $pricePayload['price_monthly'],
            'price_yearly' => $pricePayload['price_yearly'],
            'storage_gb' => $package->storage_gb,
            'max_doctors' => $package->max_doctors,
            'max_patients' => $package->max_patients,
            'features' => $package->featureMap(),
        ];
    }

    private function subscriptionPayload(?Subscription $subscription): ?array
    {
        if (! $subscription) {
            return null;
        }

        return [
            'id' => $subscription->id,
            'tenant_id' => $subscription->tenant_id,
            'doctor_id' => $subscription->doctor_id,
            'package_id' => $subscription->package_id,
            'package_name' => $subscription->package?->name,
            'billing_cycle' => $subscription->billing_cycle,
            'status' => $subscription->status,
            'starts_at' => $subscription->starts_at?->toISOString(),
            'ends_at' => $subscription->ends_at?->toISOString(),
        ];
    }

    private function paymentPayload(?Payment $payment): ?array
    {
        if (! $payment) {
            return null;
        }

        return [
            'id' => $payment->id,
            'user_id' => $payment->user_id,
            'package_id' => $payment->package_id,
            'amount' => (float) $payment->amount,
            'package_amount' => (float) $payment->package_amount,
            'discount_amount' => (float) $payment->discount_amount,
            'status' => $payment->status,
            'billing_cycle' => $payment->billing_cycle,
        ];
    }

    private function quotePayload(array $quote, PricingService $pricing, array $pricingContext): array
    {
        return [
            'package' => $this->packagePayload($quote['package'], $pricing, $pricingContext),
            'current_subscription' => $this->subscriptionPayload($quote['current_subscription']),
            'billing_cycle' => $quote['billing_cycle'],
            'package_price' => $quote['package_price'],
            'proration_credit' => $quote['proration_credit'],
            'amount_due' => $quote['amount_due'],
            'display' => [
                'currency' => $pricingContext['currency_code'],
                'currency_symbol' => $pricingContext['currency_symbol'],
                'package_price' => $pricing->convertFromUsd($quote['package_price'], $pricingContext['currency_code']),
                'proration_credit' => $pricing->convertFromUsd($quote['proration_credit'], $pricingContext['currency_code']),
                'amount_due' => $pricing->convertFromUsd($quote['amount_due'], $pricingContext['currency_code']),
            ],
        ];
    }
}
