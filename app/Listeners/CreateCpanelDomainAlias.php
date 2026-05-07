<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateCpanelDomainAlias
{
    public function handle(\App\Events\TenantDomainCreated $event)
    {
        $domain   = $event->domain->domain;
        $whmHost  = rtrim(config('services.whm.host'), '/');   // e.g. https://icircles.app:2087
        $whmToken = config('services.whm.token');
        $cpUser   = config('services.cpanel.user');            // e.g. localgovbd
        $vhost    = config('services.cpanel.main_domain', 'doctorsprofile.xyz');

        Log::info('CreateCpanelDomainAlias calling', compact('domain','whmHost','cpUser'));

        $resp = Http::withoutVerifying()->timeout(120)
    ->connectTimeout(10)
    ->retry(2, 1500)
            ->withHeaders(['Authorization' => 'whm root:' . $whmToken])
            ->get($whmHost . '/json-api/create_parked_domain_for_user', [
                'api.version'      => 1,
                'domain'           => $domain,
                'username'         => $cpUser,
                'web_vhost_domain' => $vhost,
            ]);

        if ($resp->failed()) {
            Log::error('WHM park domain failed (HTTP)', ['domain' => $domain, 'body' => $resp->body()]);
            return;
        }

        $json = $resp->json();
        $ok = (int) data_get($json, 'metadata.result', 0) === 1;
        $ok ? Log::info('WHM park domain OK', $json) : Log::error('WHM park domain error', $json);
    }
}
