<?php

namespace Colin\Api\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class ApiMiddlewareServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->middleware([HandleVerifySign::class]);
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'middleware');
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/middleware.php';
    }
}