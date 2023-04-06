<?php

namespace Vcian\LaravelDBPlayground\Providers;

use Illuminate\Support\ServiceProvider;

class DBAuditServiceProvider extends ServiceProvider
{

    protected $commands = [
        'Vcian\LaravelDBPlayground\Commands\DBAuditCommand',
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
        $this->loadViewsFrom(__DIR__.'/../views', 'DBPlayground');
    }
}
