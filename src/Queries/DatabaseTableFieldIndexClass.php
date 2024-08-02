<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Audit;

Class DatabaseTableFieldIndexClass
{
    protected string $driver, $database;

    public function __construct(protected string $table, protected  string $fieldName = 'null')
    {
        $this->driver = connection_driver();
        $this->database = database_name();
    }

    public function __invoke(): bool
    {
        return match ($this->driver) {
            'sqlite' => $this->sqlite(),
            'pgsql' => $this->pgsql(),
            default => $this->mysql(),
        };
    }


    public function mysql(): bool
    {
        $indexList = DB::select("SHOW INDEX FROM " . database_name() . "." . $this->table . "");
        foreach ($indexList as $data) {
            if ($data->Column_name === $this->fieldName && str_contains($data->Key_name, 'index')) {
                return Constant::STATUS_TRUE;
            }
        }

        return Constant::STATUS_FALSE;
    }

    public function sqlite(): bool
    {
        return Constant::STATUS_FALSE;
    }

    public function pgsql(): bool
    {
        $indexList = DB::select("SELECT
                i.relname AS index_name,
                a.attname AS column_name,
                ix.indisunique AS is_unique,
                ix.indisprimary AS is_primary
            FROM
                pg_class t,
                pg_class i,
                pg_index ix,
                pg_attribute a
            WHERE
                t.oid = ix.indrelid
                AND i.oid = ix.indexrelid
                AND a.attrelid = t.oid
                AND a.attnum = ANY(ix.indkey)
                AND t.relkind = 'r'
                AND t.relname = ?
            ORDER BY
                t.relname,
                i.relname;",[$this->table]);

        foreach ($indexList as $data) {
            if ($data->column_name === $this->fieldName && str_contains($data->index_name, 'index')) {
                return Constant::STATUS_TRUE;
            }
        }

        return Constant::STATUS_FALSE;
    }

}
