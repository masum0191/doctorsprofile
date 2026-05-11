<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Package;
use App\Models\Subscription;
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
        $package = $this->resolvePackage();

        if (!$package || !$package->hasFeature($feature)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your current package does not allow this feature.',
                    'feature' => $feature,
                ], 403);
            }

            if (!auth()->check()) {
                return back()->with('error', 'This feature is not available on the current package.');
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Your current package does not allow access to this section.');
        }

        return $next($request);
    }

    private function resolvePackage(): ?Package
    {
        $tenant = function_exists('tenant') ? tenant() : null;

        if ($tenant) {
            $subscription = Subscription::where('tenant_id', tenant('id'))
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->with('package')
                ->latest()
                ->first();

            if ($subscription && $subscription->package) {
                return $subscription->package;
            }
        }

        $packageId = data_get($tenant, 'package_id')
            ?: data_get($tenant, 'data.package_id')
            ?: auth()->user()?->package_id
            ?: auth()->user()?->package;

        return $packageId ? Package::find($packageId) : null;
    }
}
