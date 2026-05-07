<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaticApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-API-TOKEN');

        if ($token !== config('services.static_api.token')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized API request'
            ], 401);
        }

        return $next($request);
    }
}
