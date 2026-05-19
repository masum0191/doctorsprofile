<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RegistrationPaymentGatewayResolver
{
    public const DEFAULT_LOCAL_GATEWAY = 'sslcommerz';
    public const DEFAULT_INTERNATIONAL_GATEWAY = 'stripe';

    public function resolve(?string $country, ?string $requestedGateway = null): string
    {
        $country = $this->normalizeCountry($country);
        $requestedGateway = $this->normalizeGateway($requestedGateway);

        $countryGateway = $this->countryGateway($country);
        if ($countryGateway !== null) {
            return $countryGateway;
        }

        if ($this->isBangladesh($country)) {
            return self::DEFAULT_LOCAL_GATEWAY;
        }

        return self::DEFAULT_INTERNATIONAL_GATEWAY;
    }

    public function isBangladesh(?string $country): bool
    {
        $country = $this->normalizeCountry($country);

        return in_array($country, ['bangladesh', 'bd', 'ban'], true);
    }

    public function stripeSecret(): ?string
    {
        $settings = $this->centralPaymentSettings();

        return $settings['stripe']['secret']
            ?? config('services.stripe.secret')
            ?? env('STRIPE_SECRET')
            ?: null;
    }

    public function sslcommerzStoreId(): ?string
    {
        $settings = $this->centralPaymentSettings();

        return $settings['sslcommerz']['store_id']
            ?? config('sslcommerz.apiCredentials.store_id')
            ?? env('SSLCZ_STORE_ID')
            ?: null;
    }

    public function sslcommerzStorePassword(): ?string
    {
        $settings = $this->centralPaymentSettings();

        return $settings['sslcommerz']['store_password']
            ?? $settings['sslcommerz']['secret']
            ?? config('sslcommerz.apiCredentials.store_password')
            ?? env('SSLCZ_STORE_PASSWORD')
            ?: null;
    }

    public function sslcommerzApiDomain(): string
    {
        $settings = $this->centralPaymentSettings();
        $testMode = (bool) ($settings['sslcommerz']['test_mode'] ?? env('SSLCZ_TESTMODE', false));

        return $testMode ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com';
    }

    public function stripeKey(): ?string
    {
        $settings = $this->centralPaymentSettings();

        return $settings['stripe']['key']
            ?? config('services.stripe.key')
            ?? env('STRIPE_KEY')
            ?: null;
    }

    private function countryGateway(string $country): ?string
    {
        if ($country === '') {
            return null;
        }

        $settings = $this->centralPaymentSettings();
        $gateways = $settings['country_gateways']
            ?? $settings['country_based_gateways']
            ?? $settings['country_gateways'] ?? [];

        if (!is_array($gateways)) {
            return null;
        }

        foreach ($gateways as $key => $value) {
            if (is_array($value)) {
                $mappedCountry = $this->normalizeCountry($value['country'] ?? $key);
                $gateway = $value['gateway'] ?? $value['payment_gateway'] ?? null;
                $enabled = $value['enabled'] ?? true;
            } else {
                $mappedCountry = $this->normalizeCountry((string) $key);
                $gateway = $value;
                $enabled = true;
            }

            if (!$enabled || $mappedCountry !== $country) {
                continue;
            }

            $gateway = $this->normalizeGateway($gateway);
            if ($gateway !== null) {
                return $gateway;
            }
        }

        return null;
    }

    private function centralPaymentSettings(): array
    {
        try {
            if (Schema::connection('mysql')->hasTable('company_settings')) {
                $companySetting = DB::connection('mysql')->table('company_settings')->first();
                if ($companySetting && !empty($companySetting->payment_gateway)) {
                    $paymentGateway = json_decode($companySetting->payment_gateway, true);
                    if (is_array($paymentGateway)) {
                        return $paymentGateway;
                    }
                }
            }

            if (!Schema::connection('mysql')->hasTable('settings')) {
                return [];
            }

            $row = DB::connection('mysql')->table('settings')->first();
            if (!$row) {
                return [];
            }

            $settings = [];

            if (Schema::connection('mysql')->hasColumn('settings', 'extra_data') && !empty($row->extra_data)) {
                $extraData = json_decode($row->extra_data, true);
                if (is_array($extraData)) {
                    $settings = $extraData['payment'] ?? [];
                }
            }

            if (Schema::connection('mysql')->hasColumn('settings', 'payment_gateway') && !empty($row->payment_gateway)) {
                $paymentGateway = json_decode($row->payment_gateway, true);
                if (is_array($paymentGateway)) {
                    $settings = array_replace_recursive($settings, $paymentGateway);
                }
            }

            return $settings;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function normalizeCountry(?string $country): string
    {
        return Str::of($country ?? '')->trim()->lower()->replace(['_', '-'], ' ')->squish()->toString();
    }

    private function normalizeGateway($gateway): ?string
    {
        if (!is_string($gateway) || trim($gateway) === '') {
            return null;
        }

        $gateway = Str::of($gateway)->trim()->lower()->replace(['-', ' '], '_')->toString();

        return match ($gateway) {
            'ssl', 'ssl_commerz', 'sslcommerce', 'ssl_commerce' => 'sslcommerz',
            'stripe', 'paypal', 'bank_transfer', 'credit_card', 'offline' => $gateway,
            default => null,
        };
    }
}
