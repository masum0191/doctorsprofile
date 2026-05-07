<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
  'whm' => [
    'host'  => env('WHM_HOST'),
    'token' => env('WHM_TOKEN'),
],
'cpanel' => [
    'host'   => env('CPANEL_HOST'),
    'user'   => env('CPANEL_USER'),
    'token'  => env('CPANEL_TOKEN'),
    'docroot'=> env('CPANEL_DOCROOT', 'public_html'),
],
'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
],
'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
],

'static_api' => [
    'token' => env('STATIC_API_TOKEN'),
],

'whoisfreaks'=>[
    'api_key'=>env('WHOISFREAKS_API_KEY')
]

];
