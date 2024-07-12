<?php

namespace Vcian\LaravelDBAuditor\Providers;

use Illuminate\Support\ServiceProvider;

class DBAuditorServiceProvider extends ServiceProvider
{
    protected array $commands = [
        'Vcian\LaravelDBAuditor\Commands\DBAuditCommand',
        'Vcian\LaravelDBAuditor\Commands\DBStandardCommand',
        'Vcian\LaravelDBAuditor\Commands\DBConstraintCommand',
        'Vcian\LaravelDBAuditor\Commands\DBSummaryCommand',
        'Vcian\LaravelDBAuditor\Commands\DBTrackCommand',
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
        $this->publishes([
            __DIR__.'/../resource/images' => public_path('auditor/icon'),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/../views', 'DBAuditor');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadTranslationsFrom(__DIR__.'/../Lang/', 'Lang');
    }
}
