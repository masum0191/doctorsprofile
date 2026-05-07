<?php

namespace App\Observers;

use App\Models\Domain; // your Eloquent model that stores tenant domains
use App\Services\CpanelDomainService;
use Illuminate\Support\Facades\Log;

class DomainObserver
{
    public function created(Domain $domain): void
    {
        try {
            $svc = app(CpanelDomainService::class);
            $res = $svc->ensureAddonDomain($domain->domain); // docroot defaults to public_html/<domain>

            if (!($res['ok'] ?? false)) {
                Log::warning('AddonDomain create failed', ['domain' => $domain->domain, 'res' => $res]);
            } else {
                Log::info('AddonDomain ensured', ['domain' => $domain->domain, 'res' => $res['data'] ?? 'exists']);
            }
        } catch (\Throwable $e) {
            Log::error('AddonDomain exception', ['domain' => $domain->domain, 'error' => $e->getMessage()]);
        }
    }
}