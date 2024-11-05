<?php

return [
    'gateway' => [
        'base_url' => env('IPG_API_BASE_URL').'api/v2/',
        'endpoint' => 'gw/bill',
        'client_id' => env('IPG_API_CLIENT_ID'),
        'client_secret' => env('IPG_API_CLIENT_SECRET'),
        'api_token' => env('IPG_API_TOKEN', ''), // signature before decoded with sha=256
        'institution_code' => env('IPG_API_INSTITUTION_CODE'),
        // 'prefix' => '859904',
    ],
    'endpoint' => [
        'access_token_url' => env('IPG_API_BASE_URL').'oauth/token', // hardcode
        'create_bill' => env('IPG_API_BASE_URL').'api/v2/gw/bill',
        'webhook_call' => env('IPG_API_BASE_URL').'{webhook-call-url}', // not implemented
    ],

];
