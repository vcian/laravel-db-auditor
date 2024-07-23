<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DatabaseSizeClass
{
    protected string $driver;
    protected string $database;

    public function __construct()
    {
        $this->driver = connection_driver();
        $this->database = database_name();
    }

    public function __invoke(): string
    {
        return match ($this->driver) {
            'sqlite' => $this->sqlite(),
            'pgsql' => $this->pgsql(),
            default => $this->mysql(),
        };
    }

    /**
     * @return string
     */
    public function sqlite(): string
    {
        $result = $this->select("SELECT 'main' as db_name, ROUND((page_count * page_size) / 1024.0 / 1024.0, 1) as size FROM pragma_page_count(),pragma_page_size()");

        return reset($result)?->size ?? Constant::DASH;
    }

    public function select($query): array
    {
        return DB::select($query);
    }

    /**
     * @param $query
     * @return array
     */

    /**
     * @return string
     */
    public function mysql(): string
    {
        $result = $this->select('SELECT table_schema as db_name, ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) "size"
                FROM information_schema.tables
                where table_schema = "' . $this->database . '" GROUP BY table_schema');

        return reset($result)?->size ?? Constant::DASH;

    }

    /**
     * pgsql size
     *
     * @return string
     */
    public function pgsql(): string
    {
        $result = collect(
            $this->select("SELECT pg_size_pretty( pg_database_size('".$this->database."') );")
        )->toArray();

        return reset($result)->pg_size_pretty ?? Constant::DASH;
    }


}
