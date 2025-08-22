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
    

'easebuzz' => [
    'key'      => env('TRIPJACK_EASEBUZZ_KEY'),
    'salt_key' => env('TRIPJACK_EASEBUZZ_SALT_KEY'),
    'mode'     => env('TRIPJACK_EASEBUZZ_MODE', 'test'), // default test
],

'tripjack_token'=>[
  
    'mode' => env('TRIPJACK_API_MODE', 'test'),

    'live' => [
        'token' => env('TRIPJACK_API_TOKEN'),
        'url' => env('TRIPJACK_URL'),
    ],

    'test' => [
        'token' => env('TRIPJACK_API_TOKEN_TEST'),
        'url' => env('TRIPJACK_URL_TEST'),
    ],
],



    'resend' => [
        'key' => env('RESEND_KEY'),
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

];
