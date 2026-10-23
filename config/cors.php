<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Diperlukan supaya frontend React (mis. http://localhost:5173 saat dev)
    | yang beda origin dari backend Laravel (mis. http://127.0.0.1:8000)
    | diizinkan browser untuk memanggil endpoint /api/*.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Untuk development bebas '*'. Untuk production, ganti dengan domain
    // frontend kamu, misal: ['https://indramayueksplor.com'].
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Karena auth kita pakai Bearer token (JWT) di header Authorization,
    // bukan cookie, ini harus tetap false. Kalau di-set true, browser
    // menolak kombinasi allowed_origins '*' + credentials true.
    'supports_credentials' => false,

];
