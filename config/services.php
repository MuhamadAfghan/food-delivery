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

    'risebowl' => [
        // WhatsApp number in international format without '+' (e.g. 62812xxxx)
        'whatsapp_number' => env('RISEBOWL_WHATSAPP_NUMBER', ''),

        // Contact phone for landing page.
        // - display: what users see (e.g. "+62 812-3456-7890")
        // - tel: used in the tel: link (e.g. "+6281234567890")
        'contact_phone_display' => env('RISEBOWL_CONTACT_PHONE_DISPLAY', '+46 738 123 123'),
        'contact_phone_tel' => env('RISEBOWL_CONTACT_PHONE_TEL', '+46738123123'),
    ],

];
