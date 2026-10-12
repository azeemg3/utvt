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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    | Website contact-form lead intake (The Royal Closet)
    | source_id 1 = "Website / Free Quote"
    | country_id 233 = United Arab Emirates
    | system_user_id = Administrator used as created_by for anonymous website leads
    */
    'website_lead' => [
        'key' => env('WEBSITE_LEAD_API_KEY', ''),
        'source_id' => env('WEBSITE_LEAD_SOURCE_ID', 1),
        'country_id' => env('WEBSITE_LEAD_COUNTRY_ID', 233),
        'system_user_id' => env('WEBSITE_LEAD_SYSTEM_USER_ID', 12),
    ],

];
