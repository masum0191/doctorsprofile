<?php

namespace App\Services;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentGatewaySettingsService
{
    public function current(): array
    {
        return CompanySetting::first()?->payment_gateway ?? [];
    }

    public function updateFromRequest(Request $request): array
    {
        $validated = $request->validate([
            'sslcommerz_enabled' => ['nullable', 'boolean'],
            'sslcommerz_store_id' => ['nullable', 'string', 'max:255'],
            'sslcommerz_store_password' => ['nullable', 'string', 'max:255'],
            'sslcommerz_test_mode' => ['nullable', 'boolean'],
            'sslcommerz' => ['nullable', 'array'],
            'sslcommerz.enabled' => ['nullable', 'boolean'],
            'sslcommerz.store_id' => ['nullable', 'string', 'max:255'],
            'sslcommerz.store_password' => ['nullable', 'string', 'max:255'],
            'sslcommerz.secret' => ['nullable', 'string', 'max:255'],
            'sslcommerz.test_mode' => ['nullable', 'boolean'],
            'stripe_enabled' => ['nullable', 'boolean'],
            'stripe_key' => ['nullable', 'string', 'max:255'],
            'stripe_secret' => ['nullable', 'string', 'max:255'],
            'stripe' => ['nullable', 'array'],
            'stripe.enabled' => ['nullable', 'boolean'],
            'stripe.key' => ['nullable', 'string', 'max:255'],
            'stripe.secret' => ['nullable', 'string', 'max:255'],
            'country_gateways' => ['nullable'],
        ]);

        $setting = CompanySetting::first() ?? new CompanySetting();
        $existingPayment = $setting->payment_gateway ?? [];
        $existingSslcommerz = $existingPayment['sslcommerz'] ?? [];
        $existingStripe = $existingPayment['stripe'] ?? [];

        $sslcommerzStorePassword = $this->secretInput($request, [
            'sslcommerz_store_password',
            'sslcommerz.store_password',
            'sslcommerz.secret',
        ]);
        if ($sslcommerzStorePassword === '') {
            $sslcommerzStorePassword = $existingSslcommerz['store_password'] ?? $existingSslcommerz['secret'] ?? null;
        }

        $stripeKey = $this->stringInput($request, ['stripe_key', 'stripe.key']);
        $stripeSecret = $this->secretInput($request, ['stripe_secret', 'stripe.secret']);
        if ($stripeSecret === '') {
            $stripeSecret = $existingStripe['secret'] ?? null;
        }

        $stripeEnabled = $this->booleanInput($request, ['stripe_enabled', 'stripe.enabled']);
        if ($stripeEnabled && ($stripeKey === '' || empty($stripeSecret))) {
            $errors = [];

            if ($stripeKey === '') {
                $errors['stripe_key'] = 'Stripe publishable key is required when Stripe is enabled.';
            }

            if (empty($stripeSecret)) {
                $errors['stripe_secret'] = 'Stripe secret key is required when Stripe is enabled.';
            }

            throw ValidationException::withMessages($errors);
        }

        $setting->payment_gateway = [
            'sslcommerz' => [
                'enabled' => $this->booleanInput($request, ['sslcommerz_enabled', 'sslcommerz.enabled']),
                'store_id' => $this->nullableString($this->stringInput($request, ['sslcommerz_store_id', 'sslcommerz.store_id'])),
                'store_password' => $sslcommerzStorePassword,
                'secret' => $sslcommerzStorePassword,
                'test_mode' => $this->booleanInput($request, ['sslcommerz_test_mode', 'sslcommerz.test_mode']),
            ],
            'stripe' => [
                'enabled' => $stripeEnabled,
                'key' => $this->nullableString($stripeKey),
                'secret' => $this->nullableString($stripeSecret),
            ],
            'country_gateways' => $this->countryGateways($request->input('country_gateways', $validated['country_gateways'] ?? null)),
        ];
        $setting->save();

        return $setting->payment_gateway;
    }

    public function publicPayload(?array $payment = null): array
    {
        $payment ??= $this->current();

        return [
            'sslcommerz' => [
                'enabled' => (bool) data_get($payment, 'sslcommerz.enabled', false),
                'store_id' => data_get($payment, 'sslcommerz.store_id'),
                'store_password_configured' => filled(data_get($payment, 'sslcommerz.store_password') ?? data_get($payment, 'sslcommerz.secret')),
                'test_mode' => (bool) data_get($payment, 'sslcommerz.test_mode', false),
            ],
            'stripe' => [
                'enabled' => (bool) data_get($payment, 'stripe.enabled', false),
                'key' => data_get($payment, 'stripe.key'),
                'secret_configured' => filled(data_get($payment, 'stripe.secret')),
            ],
            'country_gateways' => data_get($payment, 'country_gateways', []),
        ];
    }

    private function booleanInput(Request $request, array $keys): bool
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                return $request->boolean($key);
            }
        }

        return false;
    }

    private function stringInput(Request $request, array $keys): string
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                return trim((string) $request->input($key));
            }
        }

        return '';
    }

    private function secretInput(Request $request, array $keys): string
    {
        return $this->stringInput($request, $keys);
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function countryGateways(mixed $value): array
    {
        if (is_array($value)) {
            return collect($value)
                ->mapWithKeys(function ($gateway, $country) {
                    if (is_array($gateway)) {
                        $country = $gateway['country'] ?? $country;
                        $gateway = $gateway['gateway'] ?? $gateway['payment_gateway'] ?? null;
                    }

                    $country = trim((string) $country);
                    $gateway = trim((string) $gateway);

                    return $country !== '' && $gateway !== ''
                        ? [$country => $gateway]
                        : [];
                })
                ->all();
        }

        $countryGateways = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) $value) as $line) {
            $line = trim($line);
            if ($line === '' || !str_contains($line, '=')) {
                continue;
            }

            [$country, $gateway] = array_map('trim', explode('=', $line, 2));
            if ($country !== '' && $gateway !== '') {
                $countryGateways[$country] = $gateway;
            }
        }

        return $countryGateways;
    }
}
