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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sms-msegat' => [
        'sms_gateway_key' => env('SMS_GATEWAY_KEY', NULL),
        'sms_sender' => env('SMS_SENDER', NULL),
        'sms_user' => env('SMS_USER', NULL),
        'sms_gateway_sms_url' => env('SMS_GATEWAY_SMS_URL', NULL),
    ],

    'payment-hyperpay' => [
        'hyper_sandbox_mode' => env('SANDBOX_MODE', true),
        'hyper_entity_id_mada' => env('ENTITY_ID_MADA'),
        'hyper_entity_id' => env('PAYMENT_ENTITY_ID'),
        'hyper_access_token' => env('PAYMENT_ACCESS_TOKEN'),
        'hyper_currency' => env('PAYMENT_CURRENCY', 'SAR'),
        'hyper_payment_url' => env('PAYMENT_URL', ''),
        'hyper_redirect_url' => '/hyperpay/finalize',
        'hyper_ssl_verifier' => env('PAYMENT_SSL_VERIFYPEER', false),
        'hyper_model' => env('PAYMENT_MODEL', class_exists(App\Models\User::class) ? App\Models\User::class : App\User::class),
    ]


];
