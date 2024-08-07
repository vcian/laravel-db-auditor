<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DatabaseCharacterSetClass
{
    protected string $driver, $database;

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
        return Constant::DASH;
    }

    /**
     * @return string
     */
    public function mysql(): string
    {
        $result = $this->select('SELECT DEFAULT_CHARACTER_SET_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $this->database . '"');

        return reset($result)?->DEFAULT_CHARACTER_SET_NAME ?? Constant::DASH;
    }

    public function select($query): array
    {
        return DB::select($query);
    }

    public function pgsql(): string
    {
        $result = $this->select("SELECT pg_encoding_to_char(encoding) AS character_set FROM pg_database WHERE datname = current_database();");

        return reset($result)?->character_set ?? Constant::DASH;
    }
}
