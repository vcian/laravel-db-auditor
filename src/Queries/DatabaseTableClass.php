<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;

class DatabaseTableClass
{
    protected string $driver;

    public function __construct()
    {
        $this->driver = connection_driver();
    }

    public function __invoke(): array
    {
        return match ($this->driver) {
            'sqlite' => $this->sqlite(),
            'pgsql' => $this->pgsql(),
            default => $this->mysql(),
        };
    }

    /**
     * @return array
     */
    public function sqlite(): array
    {
        $tables = $this->select("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY name");
        return array_column($tables, 'name');
    }

    /**
     * @param $query
     * @return array
     */
    public function select($query): array
    {
        return DB::select($query);
    }

    /**
     * @return array
     */
    public function mysql(): array
    {;
        return array_column(
            $this->select('SHOW TABLES'),
            'Tables_in_'.database_name()
        );
    }

    public function pgsql() : array
    {
        return array_column(
            $this->select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';"),
            'table_name'
        );
    }
}
