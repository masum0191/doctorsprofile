<?php
// app/Services/CpanelService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CpanelService
{
    public static function createAddonDomain(string $domain, string $documentRoot = 'public_html')
    {
        $host = config('services.cpanel.host');
        $user = config('services.cpanel.user');
        $token = config('services.cpanel.token');

        return Http::withHeaders([
            'Authorization' => "cpanel $user:$token"
        ])->get("https://$host:2083/execute/DomainInfo/addon_domains_create", [
            'newdomain' => $domain,
            'dir' => $documentRoot,
        ])->json();
    }
}