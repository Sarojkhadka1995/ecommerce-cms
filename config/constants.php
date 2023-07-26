<?php

return [
    'URL' => env('APP_URL'),
    'APP_DOMAIN' => env('APP_DOMAIN'),
    'APP_PROTOCOL' => env('APP_PROTOCOL', 'https'),
    'PREFIX' => env('PREFIX', 'system'),
    'TWOFA' => env('TWOFA', 1),
    'META' => ['meta' => [
        'copyright' => 'Copyright 2020 E.K. Solutions Pvt. Ltd.',
        'site' => env('APP_URL'),
        'emails' => ['pramesh.karmacharya@ekbana.info', 'ekbana@info.com'],
        'api' => [
            'version' => 1,
        ],
    ]],
    'FROM_MAIL' => env('MAIL_FROM_ADDRESS', 'info@ekbana.com'),
    'FROM_NAME' => env('MAIL_FROM_NAME', 'Ekbana'),
    'DEFAULT_LOCALE' => env('DEFAULT_LOCALE', 'en'),
    'ADMIN_DEFAULT_EMAIL' => env('ADMIN_DEFAULT_EMAIL', 'info@ekbana.com'),
    'DEFAULT_LINK_EXPIRATION' => env('DEFAULT_LINK_EXPIRATION' ?? 30),
    'API_URL' => env('API_URL', 'http://ip-api.com'),
    'IP_ADDRESS' => env('IP_ADDRESS', '110.44.123.47'),

];
