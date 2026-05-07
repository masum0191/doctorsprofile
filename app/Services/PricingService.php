<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PricingService
{
    public function contextForRequest(?Request $request = null, ?string $fallbackCountry = null): array
    {
        $request ??= request();

        $currencyCode = $this->detectCurrencyCode($request, $fallbackCountry);
        $currency = $this->currencyMeta($currencyCode);

        return [
            'base_currency' => config('pricing.base_currency', 'USD'),
            'currency_code' => $currencyCode,
            'currency_symbol' => $currency['symbol'],
            'currency_name' => $currency['name'],
            'exchange_rate' => (float) $currency['rate'],
            'country' => $this->detectCountry($request, $fallbackCountry),
            'domain_prices_usd' => config('pricing.domain_prices_usd', []),
        ];
    }

    public function detectCurrencyCode(?Request $request = null, ?string $fallbackCountry = null): string
    {
        $request ??= request();

        $override = strtoupper((string) $request->query('currency', ''));
        if ($override !== '' && config("pricing.currencies.$override")) {
            return $override;
        }

        $country = $this->detectCountry($request, $fallbackCountry);
        if ($country !== '') {
            $currencyCode = config('pricing.country_currency.' . strtoupper($country));
            if ($currencyCode) {
                return $currencyCode;
            }
        }

        return 'USD';
    }

    public function detectCountry(?Request $request = null, ?string $fallbackCountry = null): string
    {
        $request ??= request();

        $candidates = [
            $request->header('CF-IPCountry'),
            $request->header('X-Country-Code'),
            $request->header('X-Country'),
            $request->header('GeoIP-Country-Code'),
            $request->server('HTTP_CF_IPCOUNTRY'),
            $fallbackCountry,
        ];

        foreach ($candidates as $candidate) {
            $value = strtoupper(trim((string) $candidate));
            if ($value !== '') {
                return $value;
            }
        }

        $detectedFromIp = $this->detectCountryFromIp($request);
        if ($detectedFromIp !== '') {
            return $detectedFromIp;
        }

        return 'US';
    }

    public function currencyMeta(string $currencyCode): array
    {
        $allCurrencies = config('pricing.currencies');
        if (!is_array($allCurrencies)) {
            $allCurrencies = [];
        }

        $usdFallback = [
            'symbol' => '$',
            'rate' => 1.00,
            'name' => 'US Dollar',
        ];

        // Get static fallback for this currency
        $staticCurrency = $allCurrencies[$currencyCode] ?? $usdFallback;
        if (!is_array($staticCurrency)) {
            $staticCurrency = $usdFallback;
        }

        // Get live rates
        $liveRates = $this->liveRates();
        
        // Use live rate if available
        if (!empty($liveRates) && isset($liveRates[$currencyCode])) {
            $liveRate = (float) $liveRates[$currencyCode];
            if ($liveRate > 0) {
                $staticCurrency['rate'] = $liveRate;
            }
        }

        return $staticCurrency;
    }

    public function convertFromUsd(float $amountUsd, string $currencyCode): float
    {
        $rate = (float) data_get($this->currencyMeta($currencyCode), 'rate', 1);
        
        if ($rate <= 0) {
            $rate = 1;
        }
        
        return round($amountUsd * $rate, 2);
    }

    public function refreshLiveRates(): array
    {
        Cache::forget('pricing:live-rates:usd');
        return $this->liveRates(true);
    }

    public function domainPriceUsd(string $type, ?string $extension = null): float
    {
        if ($type !== 'new') {
            return 0.00;
        }

        $normalizedExtension = strtolower(trim((string) $extension));
        if ($normalizedExtension !== '' && !str_starts_with($normalizedExtension, '.')) {
            $normalizedExtension = '.' . $normalizedExtension;
        }

        $prices = config('pricing.domain_prices_usd', []);

        return (float) ($prices[$normalizedExtension ?: '.com'] ?? $prices['.com'] ?? 14.99);
    }

    public function packagePayload(object $package, string $currencyCode): array
    {
        $monthlyUsd = (float) $package->price_monthly;
        $yearlyUsd = (float) $package->price_yearly;

        return [
            'price_monthly_usd' => $monthlyUsd,
            'price_yearly_usd' => $yearlyUsd,
            'price_monthly' => $this->convertFromUsd($monthlyUsd, $currencyCode),
            'price_yearly' => $this->convertFromUsd($yearlyUsd, $currencyCode),
        ];
    }

    private function liveRates(bool $force = false): array
    {
        if (!config('pricing.rates.enabled', true)) {
            return $this->getStaticRates();
        }

        $cacheKey = 'pricing:live-rates:usd';
        $ttl = (int) config('pricing.rates.cache_ttl_seconds', 43200);

        if ($force) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, $ttl, function () {
            // Try to fetch from APIs
            $rates = $this->fetchRatesFromMultipleSources();
            
            if (!empty($rates) && count($rates) > 1) {
                return $rates;
            }
            
            // Fallback to static rates
            return $this->getStaticRates();
        });
    }

    private function fetchRatesFromMultipleSources(): array
    {
        // Method 1: Try file_get_contents with context (bypasses some SSL issues)
        $rates = $this->fetchWithFileGetContents();
        if (!empty($rates)) {
            return $rates;
        }
        
        // Method 2: Try Laravel HTTP client
        $rates = $this->fetchWithLaravelHttp();
        if (!empty($rates)) {
            return $rates;
        }
        
        // Method 3: Try curl directly
        $rates = $this->fetchWithCurl();
        if (!empty($rates)) {
            return $rates;
        }
        
        return [];
    }

    private function fetchWithLaravelHttp(): array
    {
        $apis = [
            'https://api.exchangerate-api.com/v4/latest/USD',
            'https://open.er-api.com/v6/latest/USD',
            'https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json',
        ];
        
        foreach ($apis as $url) {
            try {
                Log::info('Trying Laravel HTTP: ' . $url);
                
                $response = Http::timeout(10)
                    ->withOptions([
                        'verify' => false, // Bypass SSL verification if needed
                        'headers' => [
                            'User-Agent' => 'Mozilla/5.0 (compatible; PricingBot/1.0)',
                        ]
                    ])
                    ->get($url);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $rates = $this->parseRatesFromResponse($data);
                    
                    if (!empty($rates)) {
                        Log::info('Successfully fetched rates from: ' . $url, ['rates_count' => count($rates)]);
                        return $rates;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Laravel HTTP failed for ' . $url . ': ' . $e->getMessage());
                continue;
            }
        }
        
        return [];
    }

    private function fetchWithFileGetContents(): array
    {
        $apis = [
            'https://api.exchangerate-api.com/v4/latest/USD',
            'https://open.er-api.com/v6/latest/USD',
        ];
        
        foreach ($apis as $url) {
            try {
                Log::info('Trying file_get_contents: ' . $url);
                
                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => "User-Agent: PricingBot/1.0\r\n",
                        'timeout' => 10,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]);
                
                $response = file_get_contents($url, false, $context);
                
                if ($response !== false) {
                    $data = json_decode($response, true);
                    $rates = $this->parseRatesFromResponse($data);
                    
                    if (!empty($rates)) {
                        Log::info('Successfully fetched rates via file_get_contents from: ' . $url);
                        return $rates;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('file_get_contents failed for ' . $url . ': ' . $e->getMessage());
                continue;
            }
        }
        
        return [];
    }

    private function fetchWithCurl(): array
    {
        $apis = [
            'https://api.exchangerate-api.com/v4/latest/USD',
            'https://open.er-api.com/v6/latest/USD',
        ];
        
        foreach ($apis as $url) {
            try {
                Log::info('Trying CURL: ' . $url);
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PricingBot/1.0)');
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode === 200 && $response !== false) {
                    $data = json_decode($response, true);
                    $rates = $this->parseRatesFromResponse($data);
                    
                    if (!empty($rates)) {
                        Log::info('Successfully fetched rates via CURL from: ' . $url);
                        return $rates;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('CURL failed for ' . $url . ': ' . $e->getMessage());
                continue;
            }
        }
        
        return [];
    }

    private function parseRatesFromResponse(array $data): array
    {
        $rates = [];
        
        // Format 1: ExchangeRate-API
        if (isset($data['rates']) && is_array($data['rates'])) {
            $rates = $data['rates'];
        }
        
        // Format 2: Open Exchange Rates
        if (isset($data['conversion_rates']) && is_array($data['conversion_rates'])) {
            $rates = $data['conversion_rates'];
        }
        
        // Format 3: Frankfurter
        if (isset($data['rates']) && isset($data['base']) && $data['base'] === 'USD') {
            $rates = $data['rates'];
        }
        
        // Format 4: Currency API (jsdelivr)
        if (isset($data['usd']) && is_array($data['usd'])) {
            $rates = $data['usd'];
        }
        
        if (empty($rates)) {
            return [];
        }
        
        // Filter only the currencies we need
        $supportedCurrencies = array_keys(config('pricing.currencies', []));
        $filteredRates = [];
        
        foreach ($supportedCurrencies as $currency) {
            $currency = strtoupper($currency);
            if (isset($rates[$currency])) {
                $filteredRates[$currency] = (float) $rates[$currency];
            }
        }
        
        // Always include USD
        $filteredRates['USD'] = 1.00;
        
        return $filteredRates;
    }

    private function getStaticRates(): array
    {
        $staticRates = ['USD' => 1.00];
        $currencies = config('pricing.currencies', []);
        
        foreach ($currencies as $code => $data) {
            if ($code !== 'USD' && isset($data['rate'])) {
                $staticRates[$code] = (float) $data['rate'];
            }
        }
        
        return $staticRates;
    }

    private function detectCountryFromIp(?Request $request = null): string
    {
        if (!config('pricing.country_detection.enabled', true)) {
            return '';
        }

        $request ??= request();
        $ip = trim((string) $request->ip());

        if ($ip === '' || in_array($ip, ['127.0.0.1', '::1'], true) || $this->isPrivateIp($ip)) {
            return '';
        }

        $cacheKey = 'pricing:country-ip:' . md5($ip);
        $ttl = (int) config('pricing.country_detection.cache_ttl_seconds', 86400);

        return (string) Cache::remember($cacheKey, $ttl, function () use ($ip) {
            // Try multiple IP geolocation services
            $services = [
                'https://ipapi.co/' . urlencode($ip) . '/json/',
                'https://freeipapi.com/api/json/' . urlencode($ip),
                'http://ip-api.com/json/' . urlencode($ip),
            ];
            
            foreach ($services as $url) {
                try {
                    $response = Http::timeout(5)
                        ->withOptions(['verify' => false])
                        ->get($url);
                    
                    if ($response->successful()) {
                        $data = $response->json();
                        
                        if (isset($data['country_code'])) {
                            return strtoupper(trim($data['country_code']));
                        }
                        if (isset($data['country'])) {
                            return strtoupper(trim($data['country']));
                        }
                        if (isset($data['countryCode'])) {
                            return strtoupper(trim($data['countryCode']));
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            return '';
        });
    }
    
    private function isPrivateIp(string $ip): bool
    {
        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            '127.0.0.0/8',
        ];
        
        foreach ($privateRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function ipInRange(string $ip, string $cidr): bool
    {
        if (!str_contains($cidr, '/')) {
            return false;
        }
        
        list($subnet, $mask) = explode('/', $cidr);
        $mask = (int) $mask;
        
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - $mask);
        
        return ($ipLong & $maskLong) == ($subnetLong & $maskLong);
    }
}