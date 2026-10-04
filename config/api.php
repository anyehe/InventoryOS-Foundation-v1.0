<?php
return [
    'version' => 'v1',
    'idempotency_header' => 'Idempotency-Key',
    'default_key_abilities' => ['products:read', 'inventory:read', 'sales:read', 'purchases:read', 'reports:read'],
    'max_page_size' => 100,
    'load_balancer' => [
        'algorithm' => env('LB_ALGORITHM', 'round_robin'),
        'health_path' => '/api/v1/health',
    ],
];
