<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DatabaseTableFieldTypeClass
{
    protected string $driver, $database;

    public function __construct(protected string $table, protected string $field)
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
     * @return array
     */
    public function sqlite(): array
    {

        $query = "PRAGMA table_info(`$this->table`)";
        $tableInfo = $this->select($query);
        $dataType = collect($tableInfo)->where('name',$this->field)->first();

        return ['data_type' => $dataType->type, 'size' => '-'];
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
        $query = "SELECT `DATA_TYPE`, `CHARACTER_MAXIMUM_LENGTH`, `NUMERIC_PRECISION`, `NUMERIC_SCALE`  FROM `INFORMATION_SCHEMA`.`COLUMNS`
            WHERE `TABLE_SCHEMA`= '" . $this->database . "' AND `TABLE_NAME`= '" . $this->table . "' AND `COLUMN_NAME` = '" . $this->field . "' ";
        $data = $this->select($query);

        $dataType = reset($data);

        if (in_array($dataType->DATA_TYPE, Constant::NUMERIC_DATATYPE)) {

            if($dataType->DATA_TYPE === Constant::DATATYPE_DECIMAL) {
                $size = "(". $dataType->NUMERIC_PRECISION .",". $dataType->NUMERIC_SCALE .")";
            } else {
                $size = $dataType->NUMERIC_PRECISION;
            }
        } else {
            $size = $dataType->CHARACTER_MAXIMUM_LENGTH;
        }

        if (isset($dataType->DATA_TYPE) && $dataType !== null) {
            return ['data_type' => $dataType->DATA_TYPE, 'size' => $size];
        }
    }

    public function pgsql(): array
    {
        $data = DB::select("SELECT
                column_name,
                CASE
                WHEN data_type = 'character varying' THEN 'varchar'
                WHEN data_type = 'character' THEN 'char'
                WHEN data_type = 'timestamp without time zone' THEN 'timestamp'
                ELSE data_type
                END AS data_type,
                character_maximum_length,
                is_nullable,
                CASE
                    WHEN data_type IN ('character varying', 'character') THEN character_maximum_length
                    WHEN data_type IN ('numeric', 'decimal') THEN numeric_precision
                    WHEN data_type IN ('integer', 'bigint') THEN numeric_precision
                    ELSE NULL
                END AS size
            FROM
                information_schema.columns
            WHERE
                table_schema = 'public'
                AND Column_name = ?
                AND table_name = ?",[$this->field, $this->table]
        );


        $dataTypeDetails = reset($data);

        return [
            'data_type' => $dataTypeDetails->data_type,
            'size' => $dataTypeDetails->size,
        ];
    }
}
