<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class DocumentationController extends Controller
{
    public function index()
    {
        return response()->json([
            'api' => [
                'name' => 'Doctor Registration API',
                'version' => '1.0.0',
                //'url'=>'https://doctorsprofile.xyz',
                'base_url' => url('/api/v1'),
                'authentication' => [
                    'type' => 'Bearer Token',
                    'description' => 'Token is provided after successful registration',
                ],
            ],

            'endpoints' => [
                [
                    'method' => 'GET',
                    'url' => '/packages',
                    'description' => 'Get available packages with USD base price and geolocation-based display currency',
                ],
                [
                    'method' => 'POST',
                    'url' => '/check-domain',
                    'description' => 'Check domain availability and return domain price',
                ],
                [
                    'method' => 'POST',
                    'url' => '/validate-coupon',
                    'description' => 'Validate coupon code',
                ],
                [
                    'method' => 'POST',
                    'url' => '/calculate-registration',
                    'description' => 'Calculate registration costs in USD and return display currency totals',
                ],
                [
                    'method' => 'POST',
                    'url' => '/doctor/register',
                    'description' => 'Register a new doctor',
                ],
                [
                    'method' => 'GET',
                    'url' => '/registration/status/{order_id}',
                    'description' => 'Check registration status',
                ],
            ],

            'examples' => [
                'get_packages' => [
                    'method' => 'GET',
                    'url' => '/packages',
                    'curl' => 'curl -X GET http://yourdomain.com/api/v1/packages',
                ],

                'check_domain' => [
                    'method' => 'POST',
                    'url' => '/check-domain',
                    'headers' => [
                        'Content-Type: application/json',
                    ],
                    'body' => [
                        'type' => 'new',
                        'domain' => 'drjohn',
                        'extension' => '.com',
                    ],
                    'response_fields' => [
                        'available' => true,
                        'full_domain' => 'drjohn.com',
                        'domain_price' => 14.99,
                    ],
                ],

                'validate_coupon' => [
                    'method' => 'POST',
                    'url' => '/validate-coupon',
                    'body' => [
                        'code' => 'WELCOME20',
                        'amount' => 100,
                    ],
                ],

                'calculate_registration' => [
                    'method' => 'POST',
                    'url' => '/calculate-registration',
                    'body' => [
                        'package_id' => 1,
                        'billing_cycle' => 'monthly',
                        'domain_type' => 'subdomain',
                    ],
                ],

                'register_doctor' => [
                    'method' => 'POST',
                    'url' => '/doctor/register',
                    'content_type' => 'multipart/form-data',
                    'fields' => [
                        'email' => 'doctor@example.com',
                        'phone' => '+8801712345678',
                        'password' => 'password123',
                        'password_confirmation' => 'password123',
                        'photo' => 'file',
                        'package_id' => 1,
                        'selected_billing_cycle' => 'monthly',
                        'domain_type' => 'subdomain',
                        'subdomain_name' => 'drjohn',
                        'payment_method' => 'paypal',
                        'payment_option' => 'online',
                        'terms' => true,
                        'package_price' => 29.99,
                        'domain_price' => 0,
                        'discount_amount' => 0,
                        'total_amount' => 29.99,
                    ],
                ],
            ],
        ]);
    }
}
