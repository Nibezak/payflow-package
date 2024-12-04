<?php

return [
    'default' => env('PAYMENTS_TYPE', 'offline'),

    'types' => [
        'cash-in-hand' => [
            'driver' => 'offline',
            'released' => 'payment-offline',
        ],
        'card' => [
            'driver' => 'stripe',
            'released' => 'payment-received',
        ],
    ],
];