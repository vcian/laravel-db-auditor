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

if (!function_exists('get_sqlite_database_cache_size')) {
    /**
     * @return string
     */
    function get_sqlite_database_cache_size(): string
    {
        $cacheSize = collect(DB::select('PRAGMA default_cache_size;')[0])['cache_size'];

        if ($cacheSize < 0) {
            return abs($cacheSize).' KB';
        }

        return $cacheSize.' Pages';
    }
}
