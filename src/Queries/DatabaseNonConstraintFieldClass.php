<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Audit;

Class DatabaseNonConstraintFieldClass
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
        $fields = Constant::ARRAY_DECLARATION;
        try {
            $fieldList = DB::select("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS`
            WHERE `TABLE_SCHEMA`= '" . database_name() . "' AND `TABLE_NAME`= '" . $this->table . "' AND ( `COLUMN_KEY` = '' OR `COLUMN_KEY` = 'UNI' ) ");

            foreach ($fieldList as $field) {
                if (!in_array($field->DATA_TYPE, Constant::RESTRICT_DATATYPE)) {
                    if (!$this->checkFieldHasIndex($this->table, $field->COLUMN_NAME)) {
                        if (str_contains($field->DATA_TYPE, "int")) {
                            $fields['integer'][] = $field->COLUMN_NAME;
                        }
                        $fieldDetails = $this->getFieldDataType($this->table, $field->COLUMN_NAME);

                        if ($fieldDetails['size'] <= Constant::DATATYPE_VARCHAR_SIZE) {
                            $fields['mix'][] = $field->COLUMN_NAME;
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fields;
    }

    public function pgsql(): array
    {
        $fields = Constant::ARRAY_DECLARATION;
        try {
            $fieldList = collect(DB::select("SELECT
                a.attname AS column_name,
                CASE
                    WHEN format_type(a.atttypid, a.atttypmod) LIKE 'character varying%' THEN
                        REPLACE(format_type(a.atttypid, a.atttypmod), 'character varying', 'varchar')
                    ELSE
                        format_type(a.atttypid, a.atttypmod)
                END AS data_type
                FROM
                    pg_attribute a
                JOIN
                    pg_class t ON a.attrelid = t.oid
                LEFT JOIN
                    pg_constraint c ON t.oid = c.conrelid AND (a.attnum = ANY(c.conkey))
                WHERE
                    t.relname = ?
                    AND a.attnum > 0
                    AND NOT a.attisdropped
                    AND c.conrelid IS NULL
                ORDER BY
                    a.attnum;", [$this->table]));

            foreach ($fieldList as $field) {
                if (!in_array($field->data_type, Constant::RESTRICT_DATATYPE)) {
                    if (!$this->checkFieldHasIndex($this->table, $field->column_name)) {

                        if (str_contains($field->data_type, "int")) {
                            $fields['integer'][] = $field->column_name;
                        }
                        $fieldDetails = $this->getFieldDataType($this->table, $field->column_name);


                        if ($fieldDetails['size'] <= Constant::DATATYPE_VARCHAR_SIZE) {
                            $fields['mix'][] = $field->column_name;
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $fields;

    }

}
