<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Package;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $feature)
    {
        $tenant = function_exists('tenant') ? tenant() : null;
        $packageId = data_get($tenant, 'package_id') ?: auth()->user()?->package;

        if (!$packageId) {
            return $next($request);
        }

        $package = Package::find($packageId);

        if (!$package || !$package->hasFeature($feature)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Your current package does not allow access to this section.');
        }

        return $next($request);
    }

}
