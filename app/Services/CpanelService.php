<?php
// app/Services/CpanelService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class CpanelService
{
    public static function createAddonDomain(string $domain, string $documentRoot = 'public_html')
    {
        $host = config('services.cpanel.host');
        $user = config('services.cpanel.user');
        $token = config('services.cpanel.token');

        if (blank($host) || blank($user) || blank($token)) {
            throw new RuntimeException('cPanel credentials are not configured.');
        }

        $host = preg_replace('#^https?://#', '', rtrim((string) $host, '/'));
        $authorization = sprintf('cpanel %s:%s', $user, $token);

        return Http::withHeaders([
            'Authorization' => $authorization,
        ])->get("https://$host:2083/execute/DomainInfo/addon_domains_create", [
            'newdomain' => $domain,
            'dir' => $documentRoot,
        ])->json();
    }
}
