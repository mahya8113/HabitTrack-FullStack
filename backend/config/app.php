<?php
return [
    'name' => env('APP_NAME', 'HabitTrack'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    // "today" on the server must match the user's day. The frontend sends local dates,
    // so the server uses the same timezone (default Asia/Tehran; set APP_TIMEZONE to change).
    'timezone' => env('APP_TIMEZONE', 'Asia/Tehran'),
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => array_filter(explode(',', (string) env('APP_PREVIOUS_KEYS', ''))),
    'maintenance' => ['driver' => env('APP_MAINTENANCE_DRIVER', 'file'), 'store' => env('APP_MAINTENANCE_STORE', 'database')],
];
