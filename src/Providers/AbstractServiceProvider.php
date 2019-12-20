<?php

namespace Colin\Api\Middleware\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Colin\Api\Middleware\HandleVerifySign;

abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'api.sign'       => HandleVerifySign::class,
    ];
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api' => [
            'api.sign',
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    abstract public function boot();

    public function register()
    {
        //
    }
}