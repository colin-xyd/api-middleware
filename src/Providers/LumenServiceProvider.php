<?php

namespace Colin\Api\Middleware\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Colin\Api\Middleware\HandleVerifySign;

class LumenServiceProvider extends AbstractServiceProvider
{
    public function boot()
    {
        app()->configure('middleware');

        $path = realpath(__DIR__.'/../../config/middleware.php');

        $this->mergeConfigFrom($path, 'middleware');

        app()->routeMiddleware($this->routeMiddleware);
    }

}