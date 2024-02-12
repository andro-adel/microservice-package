<?php

use Illuminate\Support\Str;

return [
    'logging.channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'loki' => [
            'driver'         => 'monolog',
            'level'          => env('LOG_LEVEL', 'debug'),
            'handler'        => \Itspire\MonologLoki\Handler\LokiHandler::class,
            'formatter'      => \Itspire\MonologLoki\Formatter\LokiFormatter::class,
            'handler_with'   => [
                'apiConfig'  => [
                    'entrypoint'  => env('LOKI_ENTRYPOINT', "http://loki:3100"),
                ],
            ],
        ],
    ],
    'database.redis' => [
        'client' => 'predis',

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],
];
