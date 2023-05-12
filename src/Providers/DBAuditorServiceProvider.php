<?php

namespace Vcian\LaravelDBAuditor\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DBAuditorServiceProvider extends ServiceProvider
{
    protected array $commands = [
        'Vcian\LaravelDBAuditor\Commands\DBAuditCommand',
        'Vcian\LaravelDBAuditor\Commands\DBStandardCommand',
        'Vcian\LaravelDBAuditor\Commands\DBConstraintCommand'
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
        $this->loadViewsFrom(__DIR__ . '/../views', 'DBAuditor');
        $this->loadTranslationsFrom(__DIR__ . '/../Lang/', 'Lang');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        //Track database Queries
        $this->databaseQueryListen();
    }

    /**
     * @return bool
     */
    public function databaseQueryListen(): bool
    {
        try {
            DB::listen(function ($query) {
                $rowQuery = vsprintf(
                    str_replace('?', '%s', $query->sql),
                    collect($query->bindings)->map(function ($binding) {
                        return is_numeric($binding) ? $binding : "'{$binding}'";
                    })->toArray());

                $method = (str_contains($rowQuery, Constant::SELECT)) ? Constant::GET : Constant::POST;

                File::append(
                    storage_path(Constant::QUERY_LOG_FILE_PATH . Constant::DEFAULT_QUERY_LOG_FILENAME),
                    '[' . date(Constant::DATE_AND_TIME_FORMAT) . '] : ' . $method . ' : ' . $rowQuery . ' : ' . $query->time . PHP_EOL . PHP_EOL
                );
            });
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }

        return Constant::STATUS_TRUE;
    }
}
