<?php

namespace Colin\Api\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class ApiMiddlewareServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'middleware');
        $this->registerMiddleware();
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/middleware.php';
    }

    /**
     * Register the middleware.
     * @return void
     */
    protected function registerMiddleware()
    {
        app()->middleware([HandleVerifySign::class]);
    }
}