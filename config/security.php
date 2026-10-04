<?php
return [
    'api_key_header' => env('API_KEY_HEADER', 'X-API-Key'),
    'api_rate_limit' => (int) env('API_RATE_LIMIT', 60),
    'api_rate_window' => (int) env('API_RATE_WINDOW', 60),
    'cors_allowed_origins' => array_values(array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost'))))),
    'max_request_id_length' => 100,
    'session' => [
        'secure_cookie' => (bool) env('SESSION_SECURE_COOKIE', false),
        'http_only' => true,
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
    ],
    'password' => [
        'min_length' => (int) env('PASSWORD_MIN_LENGTH', 12),
        'require_mixed_case' => true,
        'require_numbers' => true,
        'require_symbols' => true,
    ],
];
