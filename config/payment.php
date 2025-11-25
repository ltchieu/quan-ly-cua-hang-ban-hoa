<?php

return [


    'vnpay' => [
        'tmn_code' => env('VNPAY_TMNCODE', env('VNP_TMN_CODE')),
        'hash_secret' => env('VNPAY_HASHSECRET', env('VNP_HASH_SECRET')),
        'url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNP_RETURN_URL'),
    ],
];
