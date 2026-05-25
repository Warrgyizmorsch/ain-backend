<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'leads/*'], // Apne leads wale path ko bhi add karein

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:8000', // Agar aapka frontend yahan chal raha hai
        'http://127.0.0.1:8000',
        'https://zudio.in',
        'https://admin.zudio.in'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // ISKO TRUE KARNA HAI
];
