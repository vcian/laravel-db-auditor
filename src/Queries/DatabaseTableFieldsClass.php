<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;

class DatabaseTableFieldsClass
{
    protected string $driver, $database;

    public function __construct(protected string $table)
    {
        $this->driver = connection_driver();
        $this->database = database_name();
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
        $fields = $this->select("PRAGMA table_info(`$this->table`)");
        return array_column($fields, 'name');
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
        $fields = $this->select("Describe `$this->table`");
        return array_column($fields, 'Field');
    }

    public function pgsql(): array
    {
        $fields = DB::select(
            "SELECT column_name,data_type, character_maximum_length, is_nullable,column_default
                    FROM
                        information_schema.columns
                    WHERE
                        table_schema = 'public'
                      AND table_name = ?",[$this->table]
        );

        return array_column($fields, 'column_name');
    }
}
