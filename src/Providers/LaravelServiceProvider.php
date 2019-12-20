<?php

namespace Colin\Api\Middleware\Providers;

class LaravelServiceProvider extends AbstractServiceProvider
{

    public function boot()
    {
        $path = realpath(__DIR__.'/../../config/middleware.php');

        $this->publishes([$path => config_path('middleware.php')], 'config');

        $this->mergeConfigFrom($path, 'middleware');

        $this->routeMiddleware();
    }


    /**
     * Register the middleware.
     * @return void
     */
    protected function routeMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}