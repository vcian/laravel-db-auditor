<?php

namespace Vcian\LaravelDBPlayground\Providers;

use Illuminate\Support\ServiceProvider;

class DBPlaygroundServiceProvider extends ServiceProvider
{

    protected $commands = [
        'Vcian\LaravelDBPlayground\Commands\DBPlayground',
        'Vcian\LaravelDBPlayground\Commands\DBStandardCommand'
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'DBPlayground');
    }
}
