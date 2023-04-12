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
            $fieldDetails = DB::select('Describe '. $tableName);
            foreach($fieldDetails as $field) {
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

            if(in_array($tableName, $tables)) {
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
    public function getFieldWithType(string $tableName)
    {
        $fieldsWithType = Constant::ARRAY_DECLARATION;
        try {
            $fieldsWithType = DB::select('Describe '. $tableName);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fieldsWithType;
    }


}
