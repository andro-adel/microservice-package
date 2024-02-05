<?php

namespace Andro\MicroservicePackage;

class MicroservicePackageServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->loadTrait();
        MicroservicePackage::setup();
    }

    protected function loadTrait()
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Traits' . DIRECTORY_SEPARATOR . 'ApiResponseTrait.php';
    }

}
