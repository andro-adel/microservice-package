<?php

namespace DD\MicroserviceCore;

use Illuminate\Support\ServiceProvider;

class MicroserviceCoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../vendor/knuckleswtf/scribe/config/scribe.php' => $this->app->configPath('scribe.php'),
        ]);
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
