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
                        array_push($tableList, $tableName);
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
     * @param string
     */
    public function getFields(string $tableName)
    {
        $fields = Constant::ARRAY_DECLARATION;
        try {
            $fieldDetails = DB::select('Describe ' . $tableName);
            foreach ($fieldDetails as $field) {
                array_push($fields, $field->Field);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fields;
    }

    /**
     * Check Table exist or not
     */
    public function checkTableExist($tableName)
    {
        try {
            $tables = $this->getTableList();

            if (in_array($tableName, $tables)) {
                return true;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return false;
    }

    /**
     * Get Field With Type By Table
     * @param string $tableName
     */
    public function getFieldsDetails(string $tableName)
    {
        $fieldsWithType = Constant::ARRAY_DECLARATION;
        try {
            $fieldsWithType = DB::select("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                            WHERE `TABLE_SCHEMA`= '" . env('DB_DATABASE') . "' AND `TABLE_NAME`= '" . $tableName . "' ");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fieldsWithType;
    }

    /**
     * Get Table Size
     * @param string $tableName
     */
    public function getTableSize(string $tableName)
    {
        try {
            $query = 'SELECT 
                    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024),2) AS `size` FROM information_schema.TABLES 
                    WHERE 
                        TABLE_SCHEMA = "' . env('DB_DATABASE') . '" AND TABLE_NAME = "' . $tableName . '" 
                    ORDER BY 
                        (DATA_LENGTH + INDEX_LENGTH) DESC';
            $result = DB::select($query);
            return $result[0]->size;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
