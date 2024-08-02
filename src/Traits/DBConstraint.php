<?php

namespace Vcian\LaravelDBAuditor\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Queries\DatabaseConstraintListClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseNonConstraintFieldClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseTableFieldIndexClass;

trait DBConstraint
{
    use DBFunctions;

    public function getNoConstraintFields(string $tableName): array
    {
        $constraint = new DatabaseNonConstraintFieldClass($tableName);
        return $constraint();
    }

    /**
     * Get Constraint List
     * @param string $tableName
     * @param array $fields
     * @return array
     */
    public function getConstraintList(string $tableName, array $fields): array
    {
        $constraint = new DatabaseConstraintListClass($tableName, $fields);
        return $constraint();
    }

    /**
     * Get constraint fields
     * @param string $tableName
     * @param string $input
     * @return array
     */
    public function getConstraintField(string $tableName, string $input): array
    {
        try {
            $constraintFields = Constant::ARRAY_DECLARATION;

            if (!$this->checkTableExist($tableName)) {
                return [];
            }

            if ($input === Constant::CONSTRAINT_INDEX_KEY) {
                $result = DB::select("SHOW INDEX FROM `{$tableName}` where Key_name != 'PRIMARY' and Key_name not like '%unique%'");
            } else {
                $result = DB::select("SHOW KEYS FROM `{$tableName}` WHERE Key_name LIKE '%" . strtolower($input) . "%'");
            }


            if ($input === Constant::CONSTRAINT_FOREIGN_KEY) {
                return $this->getForeignKeyDetails($tableName);
            }

            if ($result) {
                foreach ($result as $value) {
                    $constraintFields[] = $value->Column_name;
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $constraintFields;
    }

    /**
     * get Foreign Key
     * @param string $tableName
     * @return array
     */
    public function getForeignKeyDetails(string $tableName): array
    {
        $foreignFieldDetails = Constant::ARRAY_DECLARATION;
        try {
            $resultForeignKey = DB::select("SELECT i.TABLE_SCHEMA, i.TABLE_NAME, i.CONSTRAINT_TYPE,k.COLUMN_NAME, i.CONSTRAINT_NAME,
            k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME FROM information_schema.TABLE_CONSTRAINTS i
            LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
            WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY' AND i.TABLE_SCHEMA = '" . database_name() . "' AND i.TABLE_NAME = '" . $tableName . "'");

            if ($resultForeignKey) {
                foreach ($resultForeignKey as $value) {
                    $foreignFieldDetails[] = [
                        "column_name" => $value->COLUMN_NAME,
                        "foreign_table_name" => $value->REFERENCED_TABLE_NAME,
                        "foreign_column_name" => $value->REFERENCED_COLUMN_NAME
                    ];
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $foreignFieldDetails;
    }
}
