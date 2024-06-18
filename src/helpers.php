<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('connection_driver')) {
    /**
     * @return string
     */
    function connection_driver(): string
    {
        return DB::connection()->getDriverName();
    }
}

if (!function_exists('database_name')) {
    /**
     * @return string
     */
    function database_name(): string
    {
        return DB::connection()->getDatabaseName();
    }
}
