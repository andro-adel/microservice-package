<?php

return [
    'null' => [
        'driver' => 'monolog',
        'handler' => NullHandler::class,
    ],

    'emergency' => [
        'path' => storage_path('logs/laravel.log'),
    ],
];
