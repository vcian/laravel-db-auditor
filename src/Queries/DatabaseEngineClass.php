<?php

namespace Vcian\LaravelDBAuditor\Queries;
use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DatabaseEngineClass
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
        $result = $this->select('SELECT engine FROM information_schema.Tables where TABLE_SCHEMA = "' . $this->database . '" Limit 1');

        return reset($result)?->ENGINE ?? Constant::DASH;
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
