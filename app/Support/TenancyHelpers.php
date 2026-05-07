<?php
// app/Support/TenancyHelpers.php
namespace App\Support;

use Illuminate\Support\Arr;

class TenancyHelpers
{
    public static function ensureTenantConnection(): void
    {
        // If the 'tenant' connection vanished (config cache / worker reuse), re-seed it on the fly.
        if (! config()->has('database.connections.tenant')) {
            $base = config('database.connections.mysql'); // your central connection
            // you can clone any base; just keep database => null
            $tenant = Arr::except($base, []); 
            $tenant['database'] = null;

            config(['database.connections.tenant' => $tenant]);
        }
    }
}