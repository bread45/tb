<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
     */

    'mailgun'   => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark'  => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses'       => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],
/*
'facebook'  => [
'client_id'     => '197104588996078',
'client_secret' => 'b90a8e3008bfb15306b0e8ca95a602ef',
'redirect'      => 'https://trainingblock.sgssys.info/callback/facebook',
],
 */

    'facebook'  => [
        'client_id'     => '392910195634901',
        'client_secret' => '1e7ab08565e2d20690d038edb316ef65',
        'redirect'      => 'https://trainingblockusa.com/callback/facebook',
    ],

    'google'    => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => 'https://trainingblockusa.com/auth/google/callback',
    ],

];
