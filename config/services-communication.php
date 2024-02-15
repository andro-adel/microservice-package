<?php

return [
    'base_urls' => [
        'identity' => env('IDENTITY_SERVICE_BASE_URL', 'http://localhost:8001/api/v1'),
    ],
    'version' => env('VERSION', 'v1'),
];
