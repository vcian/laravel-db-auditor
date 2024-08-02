<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;

class DatabaseFieldDetailsClass
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
        return $this->select("PRAGMA table_info(`$this->table`)");
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
        return $this->select("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS`
                            WHERE `TABLE_SCHEMA`= '" . $this->database . "' AND `TABLE_NAME`= '" . $this->table . "' ");
    }

    public function pgsql(): array
    {
        return DB::select("SELECT
                *,
                CASE
                WHEN data_type = 'character varying' THEN 'varchar'
                WHEN data_type = 'character' THEN 'char'
                WHEN data_type = 'timestamp without time zone' THEN 'timestamp'
                ELSE data_type
                END AS data_type,
                character_maximum_length,
                is_nullable,
                CASE
                    WHEN data_type IN ('character varying', 'character') THEN character_maximum_length
                    WHEN data_type IN ('numeric', 'decimal') THEN numeric_precision
                    WHEN data_type IN ('integer', 'bigint') THEN numeric_precision
                    ELSE NULL
                END AS size
            FROM
                information_schema.columns
            WHERE
                table_schema = 'public'
                AND table_name = ?",[$this->table]
            );
    }
}
