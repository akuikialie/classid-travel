<?php

return [
    'url' => env('WALLET_URL', 'https://community.class.id'),
    'bcn' => env('WALLET_BCN', ''),

    'admin' => [
        'username' => env('WALLET_ADMIN_USER', ''),
        'password' => env('WALLET_ADMIN_PASS', ''),
    ],
];
