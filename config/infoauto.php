<?php

return [
    'baseUri' => env('INFOAUTO_BASE_URI', 'https://demo.api.infoauto.com.ar'),
    'username' => env('INFOAUTO_USERNAME', 'lucianomanuel.falcone@gmail.com'),
    'password' => env('INFOAUTO_PASSWORD', 'API2021falcone'),
    'credentials-path' => env('INFOAUTO_CREDENTIALS_PATH', storage_path('infoauto-credentials.json')),
    'cache' => [
        'days' => env('INFOAUTO_CACHE_DAYS', 5),
    ],
];
