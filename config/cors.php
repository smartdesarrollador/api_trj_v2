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

    /* 'allowed_origins' => ['https://www.iatecdigital.com', 'https://iatecdigital.com'], */
    'allowed_origins' => [
        'https://admin-tarjeta-holografico.smartdigitaltec.com',
        'https://tarjeta-holografico.smartdigitaltec.com',
        'http://localhost:4200',
        'http://localhost:5000',
        'http://localhost:7000'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
