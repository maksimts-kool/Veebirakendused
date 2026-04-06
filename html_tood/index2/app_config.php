<?php

$privateSecurityDir = dirname(__DIR__, 2) . '/storage/index2-security';
$legacySecurityDir = __DIR__ . '/data/security';

return [
    'database' => [
        'host' => getenv('INDEX2_DB_HOST') ?: 'd141144.mysql.zonevs.eu',
        'user' => getenv('INDEX2_DB_USER') ?: 'd141144_maksimts',
        'password' => getenv('INDEX2_DB_PASSWORD') ?: 'Makism123.',
        'name' => getenv('INDEX2_DB_NAME') ?: 'd141144_maksimts',
        'charset' => getenv('INDEX2_DB_CHARSET') ?: 'utf8mb4',
    ],
    'security' => [
        'admin_password' => getenv('INDEX2_ADMIN_PASSWORD') ?: 'lolavalik',
        'basic_auth_user' => getenv('INDEX2_BASIC_AUTH_USER') ?: 'admin',
        'basic_auth_password' => getenv('INDEX2_BASIC_AUTH_PASSWORD') ?: 'phpantihacker',
        'auto_authorized_ips' => [
            '127.0.0.1',
            '::1',
        ],
        'authorized_ips_file' => $privateSecurityDir . '/authorized_ips.json',
        'ip_requests_file' => $privateSecurityDir . '/ip_requests.json',
        'authorized_ips_legacy_file' => $legacySecurityDir . '/authorized_ips.json',
        'ip_requests_legacy_file' => $legacySecurityDir . '/ip_requests.json',
    ],
];
