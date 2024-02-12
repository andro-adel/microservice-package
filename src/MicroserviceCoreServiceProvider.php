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
            __DIR__ . '/../config/scribe.php' => $this->app->configPath('scribe.php'),
        ], 'dd-config');
        Artisan::call("vendor:publish --tag=dd-config --force");
        $this->publishes([
            __DIR__ . '/../lang/en/response_messages.php' => $this->app->langPath('en/response_messages.php'),
            __DIR__ . '/../lang/ar/response_messages.php' => $this->app->langPath('ar/response_messages.php'),
        ], 'dd-lang');
        Artisan::call("vendor:publish --tag=dd-lang --force");
    }

    public function register()
    {
        $ddConfig = require __DIR__ . '/../config/ddconfig.php';
        config([
            'logging.channels' => $ddConfig['logging.channels'],
            'database.redis' => $ddConfig['database.redis'],
        ]);
        $this->loadTrait();
        MicroserviceCore::setup();
    }

    protected function loadTrait()
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Traits' . DIRECTORY_SEPARATOR . 'ApiResponseTrait.php';
    }
}
