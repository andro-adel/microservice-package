<?php

namespace DD\MicroserviceCore;

use Illuminate\Support\ServiceProvider;

class MicroserviceCoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/scribe.php' => $this->app->configPath('scribe.php'),
        ], 'dd-scribe-config');
    }

    public function register()
    {
        $this->loadTrait();
        MicroserviceCore::setup();
        $this->mergeConfigFrom(
            __DIR__ . '/../config/scribe.php', 'scribe'
        );
    }

    protected function loadTrait()
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Traits' . DIRECTORY_SEPARATOR . 'ApiResponseTrait.php';
    }

}
