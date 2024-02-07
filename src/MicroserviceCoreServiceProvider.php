<?php

namespace DD\MicroserviceCore;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class MicroserviceCoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/scribe.php' => $this->app->configPath('scribe.php'),
        ], 'dd-config');
        Artisan::call("vendor:publish --tag=dd-config --force");
        $this->mergeConfigFrom(__DIR__ . '/../config/predis.php', 'database');
    }

    public function register()
    {
        $this->loadTrait();
        MicroserviceCore::setup();
    }

    protected function loadTrait()
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Traits' . DIRECTORY_SEPARATOR . 'ApiResponseTrait.php';
    }

}
