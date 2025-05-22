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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'plumbr' => [
        // Base URL for the Plumbr service (inside Docker network or external)
        'base_url' => env('PLUMBR_BASE_URL', 'http://akilimo.compute'),

        // Endpoint path for the recommendation compute API
        'rec_endpoint' => env('PLUMBR_REC_ENDPOINT', '/api/v1/dst/recommendation/compute'),

        // Request timeout in seconds
        'request_timeout' => env('PLUMBR_REQUEST_TIMEOUT', 120),
    ]

];
