<?php

namespace Vcian\LaravelDBAuditor\Providers;

use Illuminate\Support\ServiceProvider;
use Vcian\LaravelDBAuditor\Commands\MySQLPerformanceCommand;

class DBAuditorServiceProvider extends ServiceProvider
{
    protected array $commands = [
        'Vcian\LaravelDBAuditor\Commands\DBAuditCommand',
        'Vcian\LaravelDBAuditor\Commands\DBStandardCommand',
        'Vcian\LaravelDBAuditor\Commands\DBConstraintCommand',
        'Vcian\LaravelDBAuditor\Commands\DBSummaryCommand',
        'Vcian\LaravelDBAuditor\Commands\DBTrackCommand',
        'Vcian\LaravelDBAuditor\Commands\CheckPerformanceParameterCommand',
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->publishConfigs();
        }

        $this->loadViewsFrom(__DIR__ . '/../views', 'DBAuditor');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadHelpers();
        $this->loadTranslationsFrom(__DIR__ . '/../Lang/', 'Lang');
    }

    /**
     * Register config
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/db-auditor.php', 'db-auditor');
        $this->mergeConfigFrom(__DIR__ . '/../Config/audit.php', 'audit');
    }

    /**
     * Publish configs
     * @return void
     */
    protected function publishConfigs(): void
    {
        $this->publishes([
            __DIR__ . '/../resource/images' => public_path('auditor/icon'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../../config/db-auditor.php' => config_path('db-auditor.php'),
        ], 'config');
    }

    /**
     * Register commands
     * @return void
     */
    public function registerCommands(): void
    {
        $this->commands($this->commands);
    }
    /**
     * Load helpers
     * @return void
     */
    protected function loadHelpers(): void
    {
        if (file_exists(__DIR__ . '/../helpers.php')) {
            require_once __DIR__ . '/../helpers.php';
        }
    }

}
