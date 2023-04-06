<?php

namespace dbplayground\Providers;

use Illuminate\Support\ServiceProvider;

class DBAuditServiceProvider extends ServiceProvider
{

    protected $commands = [
        'dbplayground\Commands\DBAuditCommand',
        'dbplayground\Commands\DBStandardCommand'
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
        $this->loadViewsFrom(__DIR__.'/../views', 'Dbaudit');
    }
}
