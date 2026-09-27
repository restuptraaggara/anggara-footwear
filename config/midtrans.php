<?php

return [
    'server_key'     => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-demo-key-12345'),
    'client_key'     => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-demo-key-12345'),
    'is_production'  => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized'   => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'         => env('MIDTRANS_IS_3DS', true),
    'snap_url'       => env('MIDTRANS_IS_PRODUCTION', false)
                            ? 'https://app.midtrans.com/snap/snap.js'
                            : 'https://app.sandbox.midtrans.com/snap/snap.js',
    'api_url'        => env('MIDTRANS_IS_PRODUCTION', false)
                            ? 'https://app.midtrans.com/snap/v1/transactions'
                            : 'https://app.sandbox.midtrans.com/snap/v1/transactions',
];
