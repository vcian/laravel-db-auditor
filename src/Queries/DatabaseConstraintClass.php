<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Audit;

class DatabaseConstraintClass
{
    use Audit;
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
            default => $this->mysql(),
        };
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

    /**
     * @param $query
     * @return array
     */
    public function select($query): array
    {
        return DB::select($query);
    }
}
