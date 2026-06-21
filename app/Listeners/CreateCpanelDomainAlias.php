<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateCpanelDomainAlias
{
    public function handle(\App\Events\TenantDomainCreated $event)
    {
        $domain   = $event->domain->domain;
        $whmHost  = config('services.whm.host');   // e.g. https://icircles.app:2087
        $whmToken = config('services.whm.token');
        $cpUser   = config('services.cpanel.user');            // e.g. localgovbd
        $vhost    = config('services.cpanel.main_domain', 'doctorsprofile.xyz');

        if (blank($whmHost) || blank($whmToken) || blank($cpUser)) {
            Log::error('WHM credentials are not configured for domain alias creation', compact('domain'));
            return;
        }

        $whmHost = rtrim((string) $whmHost, '/');
        $authorization = sprintf('whm root:%s', $whmToken);

        Log::info('CreateCpanelDomainAlias calling', compact('domain','whmHost','cpUser'));

        $resp = Http::timeout(120)
    ->connectTimeout(10)
    ->retry(2, 1500)
            ->withHeaders(['Authorization' => $authorization])
            ->get($whmHost . '/json-api/create_parked_domain_for_user', [
                'api.version'      => 1,
                'domain'           => $domain,
                'username'         => $cpUser,
                'web_vhost_domain' => $vhost,
            ]);

        if ($resp->failed()) {
            Log::error('WHM park domain failed (HTTP)', [
                'domain' => $domain,
                'status' => $resp->status(),
            ]);
            return;
        }

        $json = $resp->json();
        $ok = (int) data_get($json, 'metadata.result', 0) === 1;
        $ok
            ? Log::info('WHM park domain OK', ['domain' => $domain])
            : Log::error('WHM park domain error', [
                'domain' => $domain,
                'reason' => data_get($json, 'metadata.reason'),
            ]);
    }
}
