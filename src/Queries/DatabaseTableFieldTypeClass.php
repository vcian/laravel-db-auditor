<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DatabaseTableFieldTypeClass
{
    public function __construct(
        protected string $driver,
        protected string $database,
        protected string $table,
        protected string $field
    )
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

        if(in_array($dataType->DATA_TYPE, Constant::NUMERIC_DATATYPE)) {

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
}
