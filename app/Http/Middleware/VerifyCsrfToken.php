<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/success',
        '/cancel',
        '/fail',
        '/ipn',
        '/pay-via-ajax',
        '*/check-subdomain',
        '*/check-domain',
        '*/doctor/info/store',
        'payment/ssl/*',
        'sslcommerz/success',
        'sslcommerz/fail',
        'sslcommerz/cancel',
        'sslcommerz/*',
    ];
}
