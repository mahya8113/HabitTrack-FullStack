<?php

// Without this file the CORS middleware has no allowed origins, so the
// browser blocks every request from the Vite frontend (http://localhost:5173)
// to the API (http://127.0.0.1:8000/api).
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Development: allow any origin (e.g. the frontend opened from a LAN IP).
    // Bearer tokens are used, not cookies, so this is fine locally. Restrict it before deploying.
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Content-Disposition'],

    'max_age' => 0,

    // Bearer tokens are sent in the Authorization header, so cookies are not needed.
    'supports_credentials' => false,
];
