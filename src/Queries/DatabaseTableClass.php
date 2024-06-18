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
    {
        $tables = $this->select('SHOW TABLES');
        return array_column($tables, 'Tables_in_packages');
    }
}
