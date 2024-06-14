<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;

class DatabaseFieldDetailsClass
{
    public function __construct(protected string $driver, protected string $database, protected string $table)
    {
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
        $data =  $this->select("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS`
                            WHERE `TABLE_SCHEMA`= '" . $this->database . "' AND `TABLE_NAME`= '" . $this->table . "' ");
        dd($data);
    }
}
