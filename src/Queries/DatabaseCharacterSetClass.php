<?php

namespace Vcian\LaravelDBAuditor\Queries;
use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DatabaseCharacterSetClass
{
    public function __construct(protected string $driver, protected string $database)
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
    public function mysql(): string
    {
        $result = $this->select('SELECT DEFAULT_CHARACTER_SET_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'. $this->database .'"');

        return reset($result)?->DEFAULT_CHARACTER_SET_NAME ?? Constant::DASH;
    }

    /**
     * @return string
     */
    public function sqlite(): string
    {
        return Constant::DASH;
    }

    public function select($query): array
    {
        return DB::select($query);
    }
}
