<?php

namespace DD\MicroserviceCore;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class MicroserviceCoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ddconfig.php' => $this->app->configPath('ddconfig.php'),
            __DIR__ . '/../config/excel.php' => $this->app->configPath('excel.php'),
            __DIR__ . '/../config/scribe.php' => $this->app->configPath('scribe.php'),
            __DIR__ . '/../config/snappy.php' => $this->app->configPath('snappy.php'),
            __DIR__ . '/../config/services-communication.php' => $this->app->configPath('services-communication.php'),
        ], 'dd-config');
        Artisan::call("vendor:publish --tag=dd-config");
        $this->publishes([
            __DIR__ . '/../lang/en/response_messages.php' => $this->app->langPath('en/response_messages.php'),
            __DIR__ . '/../lang/ar/response_messages.php' => $this->app->langPath('ar/response_messages.php'),
        ], 'dd-lang');
        Artisan::call("vendor:publish --tag=dd-lang");
        $this->publishes([
            __DIR__ . '/../tests' => $this->app->basePath('tests'),
            __DIR__ . '/../phpunit.xml' => $this->app->basePath('phpunit.xml'),
        ], 'dd-tests');
        Artisan::call("vendor:publish --tag=dd-tests");
    }

    public function register()
    {
        $ddConfig = require_once __DIR__ . '/../config/ddconfig.php';
        config([
            'logging.channels' => $ddConfig['logging.channels'],
            'database.redis' => $ddConfig['database.redis'],
        ]);
        MicroserviceCore::setup();
    }
}
