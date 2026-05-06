<?php

return [
    'admin_panel_path' => trim((string) env('ADMIN_PANEL_PATH', 'admin'), '/') ?: 'admin',

    'admin_allowed_ips' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('ADMIN_ALLOWED_IPS', '')),
    ))),

    'allowed_external_hosts' => array_values(array_filter(array_map(
        fn (string $host): string => strtolower(trim($host)),
        explode(',', (string) env('SECURITY_ALLOWED_EXTERNAL_HOSTS', 'madanimontessori.online,www.madanimontessori.online,wa.me,forms.gle,docs.google.com,www.google.com,instagram.com,www.instagram.com')),
    ))),

    'admin_password_min_length' => (int) env('ADMIN_PASSWORD_MIN_LENGTH', 16),

    'public_forms' => [
        'honeypot_field' => env('PUBLIC_FORM_HONEYPOT_FIELD', 'website'),
        'max_submissions_per_number_per_hour' => (int) env('PUBLIC_FORM_MAX_PER_NUMBER_PER_HOUR', 3),
        'rate_limit_decay_seconds' => (int) env('PUBLIC_FORM_RATE_LIMIT_DECAY_SECONDS', 3600),
    ],

    'headers' => [
        'csp' => env('SECURITY_CSP', implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data: https://fonts.gstatic.com",
            "connect-src 'self' https: wss:",
            "frame-src 'self' https://www.google.com",
        ])),
    ],
];
