<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $type): Response
    {
        if (!auth()->check() || auth()->user()->role !== $type) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
