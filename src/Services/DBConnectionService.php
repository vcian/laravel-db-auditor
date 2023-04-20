<?php

namespace Vcian\LaravelDBAuditor\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;

class DBConnectionService
{

    /**
     * Get Table List
     * @return array
     */
    public function getTableList(): array
    {
        $tableList = Constant::ARRAY_DECLARATION;
        try {
            $tables = DB::select('SHOW TABLES');

            if ($tables) {
                foreach ($tables as $tableValue) {
                    foreach ($tableValue as $tableName) {
                        $tableList[] = $tableName;
                    }
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $tableList;
    }

    /**
     * Get list of datatype of field and name of the field by table name
     * @param string $tableName
     * @return array $fields
     */
    public function getFields(string $tableName): array
    {
        $fields = Constant::ARRAY_DECLARATION;
        try {
            $fieldDetails = DB::select("Describe {$tableName}");
            foreach ($fieldDetails as $field) {
                $fields[] = $field->Field;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fields;
    }

    /**
     * Check Table exist or not in the database
     * @param string $tableName
     * @return bool
     */
    public function checkTableExist(string $tableName): bool
    {
        try {
            $tables = $this->getTableList();

            if (in_array($tableName, $tables)) {
                return Constant::STATUS_TRUE;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return Constant::STATUS_FALSE;
    }

    /**
     * Get field with type by table
     * @param string $tableName
     * @return array
     */
    public function getFieldsDetails(string $tableName): array
    {
        $fieldWithType = Constant::ARRAY_DECLARATION;
        try {
            $fieldWithType = DB::select("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS`
                            WHERE `TABLE_SCHEMA`= '" . env('DB_DATABASE') . "' AND `TABLE_NAME`= '" . $tableName . "' ");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fieldWithType;
    }

    /**
     * Get Table Size
     * @param string $tableName
     * @return string
     */
    public function getTableSize(string $tableName): string
    {
        try {
            $query = 'SELECT
                    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024),2) AS `size` FROM information_schema.TABLES
                    WHERE
                        TABLE_SCHEMA = "' . env('DB_DATABASE') . '" AND TABLE_NAME = "' . $tableName . '"
                    ORDER BY
                        (DATA_LENGTH + INDEX_LENGTH) DESC';
            $result = DB::select($query);
            if ($result) {
                return $result[0]->size;
            }

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return Constant::STATUS_FALSE;
    }
}
