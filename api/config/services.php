<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'botman' => [
    'hipchat_urls' => [
        'YOUR-INTEGRATION-URL-1',
        'YOUR-INTEGRATION-URL-2',
    ],
    'nexmo_key' => 'YOUR-NEXMO-APP-KEY',
    'nexmo_secret' => 'YOUR-NEXMO-APP-SECRET',
    'microsoft_bot_handle' => 'YOUR-MICROSOFT-BOT-HANDLE',
    'microsoft_app_id' => 'YOUR-MICROSOFT-APP-ID',
    'microsoft_app_key' => 'YOUR-MICROSOFT-APP-KEY',
    'slack_token' => 'YOUR-SLACK-TOKEN-HERE',
    'telegram_token' => 'YOUR-TELEGRAM-TOKEN-HERE',
    'facebook_token' => 'EAACfJZAjQCwcBAELAQxE2wzZAtuY9G5cbUI3Y29A9W1c5boK4Ljcx9oM8q6d4QztPee2nNrJxAnY3ngZAIougw6tglHrFOSxOA6I3uZBdoOtuXkZBFAzcxKIlsJXiuWAe2vBmJQ4ltJDW3U0K0saiGZClaiyXEzpgonOD3iyRuIaQZBJA1E5csA', 
    'facebook_app_secret' => '83acd1e32930f0d4d5244532238afd29', // Optional - this is used to verify incoming API calls,
    'wechat_app_id' => 'YOUR-WECHAT-APP-ID',
    'wechat_app_key' => 'YOUR-WECHAT-APP-KEY',
    ],
];


