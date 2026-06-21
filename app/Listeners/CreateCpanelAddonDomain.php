<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Import the Log facade

class CreateCpanelAddonDomain // Renamed for clarity
{
    /**
     * Handle the event.
     *
     * @param  TenantCreated  $event
     * @return void
     */
    public function handle(TenantCreated $event): void
    {
        $tenant = $event->tenant;
        // This assumes your tenant model has a 'domains' relationship
        $domain = $tenant->domains()->first()?->domain;

        if (!$domain) {
            Log::error('No domain found for tenant: '.$tenant->id);
            return;
        }

        $whmHost  = config('services.whm.host'); // e.g., https://yourhost.com:2087
        $whmToken = config('services.whm.token');
        $cpUser   = config('services.whm.user', 'localgovbd');

        if (blank($whmHost) || blank($whmToken) || blank($cpUser)) {
            Log::error('WHM credentials are not configured for cPanel alias creation', [
                'tenant' => $tenant->id,
                'domain' => $domain,
            ]);
            return;
        }

        $whmHost = rtrim((string) $whmHost, '/');
        $authorization = sprintf('whm root:%s', $whmToken);

        try {
            $response = Http::asForm()
                ->timeout(120)
                ->connectTimeout(10)
                ->withHeaders([
                    'Authorization' => $authorization,
                ])
                // Use the correct 'uapi' endpoint
                ->post("{$whmHost}/json-api/uapi_cpanel", [
                    'cpanel_jsonapi_user'   => $cpUser,
                    'cpanel_jsonapi_module' => 'Park', // Using Park to create an Alias
                    'cpanel_jsonapi_func'   => 'park', // The function to create the Alias
                    'domain'                => $domain,
                ]);

            $result = $response->json();

            // Check if the cPanel API call itself was successful
            if (isset($result['result']['status']) && $result['result']['status'] == 1) {
                Log::info('Successfully created cPanel alias for ' . $domain, [
                    'tenant' => $tenant->id
                ]);
            } else {
                Log::error('cPanel API failed to create alias for ' . $domain, [
                    'tenant' => $tenant->id,
                    'errors' => data_get($result, 'result.errors') ?? data_get($result, 'metadata.reason')
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('HTTP request failed to create cPanel alias', [
                'tenant' => $tenant->id,
                'domain' => $domain,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
