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
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    ],
    'sms' => [
        'providers' => [
            'melliPayamak' => \App\Services\sms\Providers\MelliPayamakProvider::class,
        ],
        'default_provider' => \App\Services\sms\Providers\MelliPayamakProvider::class,
        'melliPayamak' => [
            'baseUrl' => env('MELLI_PAYAMAK_URL', 'https://rest.payamak-panel.com/api/'),
            'username' => env('MELLI_PAYAMAK_USERNAME', '9386801868'),
            'password' => env('MELLI_PAYAMAK_PASSWORD', 'bfbffccf-314b-4d91-8bd1-723087126638'),
            'fromNumber' => env('MELLI_PAYAMAK_PASSWORD', '50002710001868'),
            'otpLoginBodyID' => env('MELLI_PAYAMAK_OTP_LOGIN_BODY_ID', '246526'),
        ],
    ],
    'openai' => [
        'api_key' => env('LIARA_AI_KEY'),
        'base_url' => env('LIARA_AI_BASE_URL'),
    ]
];
