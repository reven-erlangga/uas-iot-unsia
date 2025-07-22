<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Biarkan wildcard (*) untuk development — bisa diubah ke spesifik origin nanti
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    // Kalau kamu perlu akses header tertentu dari frontend, bisa isi di sini
    'exposed_headers' => [],

    // Set ke 0 (default), artinya tidak cache preflight result
    'max_age' => 0,

    // Kalau kamu pakai cookie/auth session dari JS (credential), set true
    // Tapi untuk API dan ESP, biasanya false cukup
    'supports_credentials' => false,

];
