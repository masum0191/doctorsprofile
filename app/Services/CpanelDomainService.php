<?php

namespace App\Services;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class CpanelDomainService
{
    protected Client $http;
    protected string $host;
    protected string $user;
    protected string $token;
    protected string $defaultDocroot;

    public function __construct()
    {
        $this->host           = rtrim(config('services.cpanel.host', env('CPANEL_HOST')), '/');
        $this->user           = (string) config('services.cpanel.user', env('CPANEL_USER'));
        $this->token          = (string) config('services.cpanel.token', env('CPANEL_TOKEN'));
        $this->defaultDocroot = (string) config('services.cpanel.docroot', env('CPANEL_DOCROOT', 'public_html'));

        $this->http = new Client([
            'base_uri' => $this->host,
            'timeout'  => 20,
            'verify'   => true,
            'headers'  => [
                'Authorization' => 'cpanel ' . $this->user . ':' . $this->token,
                'Accept'        => 'application/json',
            ],
        ]);
    }

    public function addDomain(string $domain, ?string $dir = null): array
    {
        $domain = strtolower(trim($domain));
        $dir    = $dir ?: $this->defaultDocroot;

        try {
            // Domains/add_domain replaces AddonDomain/addaddondomain & Park/park
            $resp = $this->http->post('/execute/Domains/add_domain', [
                'form_params' => [
                    'domain' => $domain,
                    // If you want an alias pointing to the same docroot:
                    'dir'    => $dir,  // e.g., "public_html"
                    // If you want its own subdir, pass something like "public_html/jahanmartkidsbd"
                    // You can also pass 'dns' or 'subdomain' options — see cPanel docs for Domains/add_domain.
                ],
            ]);

            $json = $this->decode($resp);

            if ((int)($json['status'] ?? 0) === 1) {
                return ['ok' => true, 'data' => $json['data'] ?? $json];
            }

            $err = $json['errors'][0] ?? $json['error'] ?? 'Unknown error';
            // Helpful hint if the module is actually missing on the server:
            if (stripos($err, 'Failed to load module “Domains”') !== false) {
                $err .= ' (Your cPanel install is missing the Domains UAPI module; use the UI: cPanel → Domains → Create Domain, or fix the server packages.)';
            }
            return ['ok' => false, 'error' => $err, 'raw' => $json];

        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    public function listDomains(): array
    {
        try {
            $resp = $this->http->get('/execute/Domains/list_domains');
            $json = $this->decode($resp);

            if ((int)($json['status'] ?? 0) === 1) {
                return ['ok' => true, 'data' => $json['data'] ?? []];
            }

            $err = $json['errors'][0] ?? $json['error'] ?? 'Unknown error';
            if (stripos($err, 'Failed to load module “Domains”') !== false) {
                $err .= ' (Domains UAPI module missing on server.)';
            }
            return ['ok' => false, 'error' => $err, 'raw' => $json];

        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    protected function decode(ResponseInterface $resp): array
    {
        return json_decode((string) $resp->getBody(), true) ?? [];
    }
    public function ensureAddonDomain(string $domain, ?string $dir = null): array
    {
        $domainsList = $this->listDomains();
        if (!$domainsList['ok']) {
            return $domainsList; // return the error
        }

        $found = false;
        foreach ($domainsList['data'] as $d) {
            if (isset($d['domain']) && strtolower($d['domain']) === strtolower($domain)) {
                $found = true;
                break;
            }
        }

        if ($found) {
            return ['ok' => true, 'message' => 'Domain already exists.'];
        }

        // Domain not found; attempt to add it
        return $this->addDomain($domain, $dir);
    }

}
