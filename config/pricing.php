<?php

return [
    'base_currency' => 'USD',

    'country_detection' => [
        'enabled' => env('PRICING_COUNTRY_DETECTION_ENABLED', true),
        'api_url' => env('PRICING_COUNTRY_API_URL', 'https://api.country.is'),
        'cache_ttl_seconds' => env('PRICING_COUNTRY_CACHE_TTL', 86400),
    ],

    'rates' => [
        'enabled' => env('PRICING_LIVE_RATES_ENABLED', true),
        'api_url' => env('PRICING_RATES_API_URL', 'https://api.frankfurter.app/latest'),
        'fallback_urls' => [
            'https://api.frankfurter.app/latest',
            'https://open.er-api.com/v6/latest/USD',
        ],
        'request_timeout_seconds' => env('PRICING_RATES_TIMEOUT_SECONDS', 8),
        'cache_ttl_seconds' => env('PRICING_RATES_CACHE_TTL', 43200),
    ],

    // Static fallback exchange rates relative to 1 USD.
    // These can be moved to admin settings later without changing the pricing layer.
    'currencies' => [
        'USD' => ['symbol' => '$', 'rate' => 1.00, 'name' => 'US Dollar'],
        'BDT' => ['symbol' => '৳', 'rate' => 120.00, 'name' => 'Bangladeshi Taka'],
        'INR' => ['symbol' => '₹', 'rate' => 83.00, 'name' => 'Indian Rupee'],
        'PKR' => ['symbol' => '₨', 'rate' => 278.00, 'name' => 'Pakistani Rupee'],
        'AED' => ['symbol' => 'AED ', 'rate' => 3.67, 'name' => 'UAE Dirham'],
        'GBP' => ['symbol' => '£', 'rate' => 0.80, 'name' => 'British Pound'],
        'EUR' => ['symbol' => '€', 'rate' => 0.92, 'name' => 'Euro'],
        'CAD' => ['symbol' => 'C$', 'rate' => 1.37, 'name' => 'Canadian Dollar'],
        'AUD' => ['symbol' => 'A$', 'rate' => 1.54, 'name' => 'Australian Dollar'],
    ],

    'country_currency' => [
        'US' => 'USD',
        'USA' => 'USD',
        'UNITED STATES' => 'USD',
        'BD' => 'BDT',
        'BANGLADESH' => 'BDT',
        'IN' => 'INR',
        'INDIA' => 'INR',
        'PK' => 'PKR',
        'PAKISTAN' => 'PKR',
        'AE' => 'AED',
        'UAE' => 'AED',
        'UNITED ARAB EMIRATES' => 'AED',
        'GB' => 'GBP',
        'UK' => 'GBP',
        'UNITED KINGDOM' => 'GBP',
        'EU' => 'EUR',
        'DE' => 'EUR',
        'FR' => 'EUR',
        'ES' => 'EUR',
        'IT' => 'EUR',
        'NL' => 'EUR',
        'CA' => 'CAD',
        'CANADA' => 'CAD',
        'AU' => 'AUD',
        'AUSTRALIA' => 'AUD',
    ],

    'domain_prices_usd' => [
        '.com' => 14.99,
        '.net' => 16.99,
        '.org' => 15.99,
        '.info' => 13.99,
        '.xyz' => 12.99,
    ],
];
