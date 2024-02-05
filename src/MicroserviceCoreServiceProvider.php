<?php

namespace DD\MicroserviceCore;

class MicroserviceCoreServiceProvider
{
    public function boot()
    {
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
