<?php

return [
    'momo' => [
        'partner_code' => env('MOMO_PARTNER_CODE'),
        'access_key' => env('MOMO_ACCESS_KEY'),
        'secret_key' => env('MOMO_SECRET_KEY'),
    ],

    'vnpay' => [
        'tmncode' => env('VNPAY_TMNCODE', '2L61D5EB'),
        'hashsecret' => env('VNPAY_HASHSECRET', 'SFWIYVNIQBIFZGTBZQNFHAVZINDO2IPO'),
    ],
];
