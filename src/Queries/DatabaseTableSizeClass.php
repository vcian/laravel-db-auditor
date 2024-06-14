<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DatabaseTableSizeClass
{
    public function __construct(protected string $driver, protected string $database, protected string $table)
    {
    }

    public function __invoke(): string
    {
        return match ($this->driver) {
            'sqlite' => $this->sqlite(),
            default => $this->mysql(),
        };
    }

    /**
     * @return string
     */
    public function sqlite(): string
    {
        $result = $this->select('SELECT ROUND((SUM(pgsize) / 1024 / 1024), 2) AS size FROM dbstat WHERE name = "' . $this->table . '"');
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
        $result = $this->select('SELECT
                    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024),2) AS `size` FROM information_schema.TABLES
                    WHERE
                        TABLE_SCHEMA = "' . $this->database . '" AND TABLE_NAME = "' . $this->table . '"
                    ORDER BY
                        (DATA_LENGTH + INDEX_LENGTH) DESC');

        return reset($result)?->size ?? Constant::DASH;

    }


}
