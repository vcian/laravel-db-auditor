<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Audit;
use Vcian\LaravelDBAuditor\Traits\DBConstraint;

class DatabaseConstraintClass
{
    use Audit;
    protected string $driver, $database;

    public function __construct(protected string $table, protected string $fields = '')
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
     * @param $query
     * @return array
     */
    public function select($query): array
    {
        return DB::select($query);
    }

    /**
     * Sqlite query.
     *
     * @return array
     */
    public function sqlite(): array
    {
        return [
            "primary" => collect (DB::select("PRAGMA table_info($this->table)"))
                ->where('pk', 1)
                ->select('name', 'pk')
                ->toArray(),
            "foreign" => collect(DB::select("PRAGMA foreign_key_list($this->table)"))
                ->select('from', 'to', 'table')
                ->toArray(),
            "index" => collect(DB::select("PRAGMA index_list($this->table)"))
                ->select('name')
                ->toArray()
        ];
    }

    /**
     * Mysql query.
     *
     * @return array
     */
    public function mysql(): array
    {

        return [
            'primary' => $this->getConstraintField($this->table, Constant::CONSTRAINT_PRIMARY_KEY),
            'unique' => $this->getConstraintField($this->table, Constant::CONSTRAINT_UNIQUE_KEY),
            'foreign' => $this->getConstraintField($this->table, Constant::CONSTRAINT_FOREIGN_KEY),
            'index' => $this->getConstraintField($this->table, Constant::CONSTRAINT_INDEX_KEY),
        ];
    }


    public function pgsql(): array
    {
        return [
            'primary' => $this->getPgsqlConstraintField($this->table, Constant::CONSTRAINT_PRIMARY_KEY),
            'unique' => $this->getPgsqlConstraintField($this->table, Constant::CONSTRAINT_UNIQUE_KEY),
            'foreign' => $this->getPgsqlConstraintField($this->table, Constant::CONSTRAINT_FOREIGN_KEY),
            'index' => $this->getPgsqlConstraintField($this->table, Constant::CONSTRAINT_INDEX_KEY),
        ];
    }

    public function getPgsqlConstraintField(string $tableName, string $input): array
    {
        if ($input === Constant::CONSTRAINT_FOREIGN_KEY){
            return collect(DB::select("SELECT
                conname AS constraint_name,
                a.attname AS column_name,
                confrelid::regclass AS foreign_table,
                af.attname AS foreign_column
            FROM
                pg_constraint AS c
            JOIN
                pg_class AS t ON c.conrelid = t.oid
            JOIN
                pg_attribute AS a ON a.attnum = ANY(c.conkey) AND a.attrelid = t.oid
            JOIN
                pg_attribute AS af ON af.attnum = ANY(c.confkey) AND af.attrelid = c.confrelid
            WHERE
                t.relname = ?
                AND c.contype = 'f'", [$tableName]))
                ->flatten()
                ->toArray();
        }

        if ($input === Constant::CONSTRAINT_INDEX_KEY) {
            return collect(DB::select("SELECT
                    i.relname AS index_name,
                    a.attname AS column_name
                FROM
                    pg_class AS t
                JOIN
                    pg_index AS ix ON t.oid = ix.indrelid
                JOIN
                    pg_class AS i ON i.oid = ix.indexrelid
                JOIN
                    pg_attribute AS a ON a.attnum = ANY(ix.indkey) AND a.attrelid = t.oid
                WHERE t.relname = ?",[$tableName]))
                ->select('column_name')
                ->flatten()
                ->toArray();
        } else {
            $conType = Constant::CONSTRAINT_PRIMARY_KEY ? 'p' : 'u';
            return collect(DB::select("
                    SELECT
                conname AS constraint_name,
                a.attname AS column_name
            FROM
                pg_constraint AS c
            JOIN
                pg_class AS t ON c.conrelid = t.oid
            JOIN
                pg_attribute AS a ON a.attnum = ANY(c.conkey) AND a.attrelid = t.oid
            WHERE
                t.relname = ?
                AND c.contype = ?",[$tableName,$conType]))
                ->select('column_name')
                ->flatten()
                ->toArray();
        }

    }
}
