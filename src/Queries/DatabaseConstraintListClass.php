<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Audit;

class DatabaseConstraintListClass
{
    use Audit;

    protected string $driver, $database;

    public function __construct(protected string $table, protected array $fields = [])
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

    public function mysql(): array
    {
        $constrainList = Constant::ARRAY_DECLARATION;

        if (!empty($this->fields['integer'])) {

            if (!$this->tableHasValue($this->table)) {
                $constrainList[] = Constant::CONSTRAINT_FOREIGN_KEY;

                if (empty($this->getConstraintField($this->table, Constant::CONSTRAINT_PRIMARY_KEY))) {
                    $constrainList[] = Constant::CONSTRAINT_PRIMARY_KEY;
                }
            }
        }

        if (!empty($this->fields['mix'])) {
            $constrainList[] = Constant::CONSTRAINT_INDEX_KEY;

            if (!empty($this->getUniqueFields($this->table, $this->fields['mix']))) {
                $constrainList[] = Constant::CONSTRAINT_UNIQUE_KEY;
            }
        }


        return $constrainList;
    }

    public function pgsql(): array
    {
        $constraintList = Constant::ARRAY_DECLARATION;

        if (!empty($fields['integer'])) {

            if (!$this->tableHasValue($this->table)) {
                $constraintList[] = Constant::CONSTRAINT_FOREIGN_KEY;

                if (empty($this->getConstraintField($this->table, Constant::CONSTRAINT_PRIMARY_KEY))) {
                    $constraintList[] = Constant::CONSTRAINT_PRIMARY_KEY;
                }
            }
        }

        if (!empty($fields['mix'])) {
            $constraintList[] = Constant::CONSTRAINT_INDEX_KEY;

            if (!empty($this->getUniqueFields($this->table, $fields['mix']))) {
                $constraintList[] = Constant::CONSTRAINT_UNIQUE_KEY;
            }
        }
        return $constraintList;

    }
}
