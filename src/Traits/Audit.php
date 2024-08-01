<?php

namespace Vcian\LaravelDBAuditor\Traits;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Queries\DatabaseConstraintListClass;

trait Audit
{
    use DBFunctions, DBConstraint;

    /**
     * Check field exist or not
     * @param string $tableName
     * @param string $field
     * @return bool
     */
    public function checkFieldExistOrNot(string $tableName, string $field): bool
    {
        $fields = $this->getFields($tableName);
        if (in_array($field, $fields)) {
            return Constant::STATUS_TRUE;
        }
        return Constant::STATUS_FALSE;
    }


    /**
     * Check Table Has Value
     * @param string $tableName
     * @return bool
     */
    public function tableHasValue(string $tableName): bool
    {

        try {
            if (DB::select("Select * from " . $tableName)) {
                return Constant::STATUS_TRUE;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return Constant::STATUS_FALSE;
    }


    /**
     * Get Unique Fields
     * @param string $tableName
     * @param array $fields
     * @return array
     */
    public function getUniqueFields(string $tableName, array $fields): array
    {
        $uniqueField = Constant::ARRAY_DECLARATION;
        try {
            foreach ($fields as $field) {

                $getUniqueQuery = "SELECT * FROM INFORMATION_SCHEMA.STATISTICS
                          WHERE TABLE_SCHEMA = '" . database_name() . "' AND TABLE_NAME = '" . $tableName . "' AND COLUMN_NAME = '" . $field . "' AND NON_UNIQUE = 0";
                $resultUniqueQuery = DB::select($getUniqueQuery);
                if (!$resultUniqueQuery) {
                    $query = "SELECT `" . $field . "`, COUNT(`" . $field . "`) as count FROM " . $tableName . " GROUP BY `" . $field . "` HAVING COUNT(`" . $field . "`) > 1";
                    $result = DB::select($query);

                    if (empty($result)) {
                        $uniqueField[] = $field;
                    }
                }

            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $uniqueField;
    }

    /**
     * Add constraint to the field
     * @param string $table
     * @param string $field
     * @param string $constraint
     * @param string|null $referenceTableName
     * @param string|null $referenceField
     * @return bool
     */
    public function addConstraint(
        string $table,
        string $field,
        string $constraint,
        string $referenceTableName = null,
        string $referenceField = null
    ): bool
    {
        try {
            switch ($constraint) {
                case Constant::CONSTRAINT_PRIMARY_KEY:
                    $this->migrateConstrain(Constant::PRIMARY_FILE_NAME, $constraint, $table, $field);
                    break;
                case Constant::CONSTRAINT_INDEX_KEY:
                    $this->migrateConstrain(Constant::INDEX_FILE_NAME, $constraint, $table, $field);
                    break;
                case Constant::CONSTRAINT_UNIQUE_KEY:
                    $this->migrateConstrain(Constant::UNIQUE_FILE_NAME, $constraint, $table, $field);
                    break;
                case Constant::CONSTRAINT_FOREIGN_KEY:
                    $this->migrateConstrain(Constant::FOREIGN_FILE_NAME, $constraint, $table, $field, $referenceField, $referenceTableName);
                    break;
                default:
                    return Constant::STATUS_FALSE;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return Constant::STATUS_TRUE;
    }

    /**
     * Dynamic Migration
     * @param string $fileName
     * @param string $constrainName
     * @param string $tableName
     * @param string $fieldName
     * @param string|null $referenceField
     * @param string|null $referenceTableName
     * @return bool
     */
    public function migrateConstrain(
        string $fileName,
        string $constrainName,
        string $tableName,
        string $fieldName,
        string $referenceField = null,
        string $referenceTableName = null
    ): bool
    {
        try {
            $fieldDetails = $this->getFieldDataType($tableName, $fieldName);
            $fieldDataType = Constant::NULL;

            if (!empty($fieldDetails['data_type'])) {
                $fieldDataType = Constant::MYSQL_DATATYPE_TO_LARAVEL_DATATYPE[$fieldDetails['data_type']] ?? $fieldDetails['data_type'];
            }

            $stubVariables = [
                "tableName" => $tableName,
                "fieldName" => $fieldName,
                "referenceField" => $referenceField,
                "referenceTable" => $referenceTableName,
                "dataType" => $fieldDataType
            ];

            $contents = file_get_contents(__DIR__ . "/../Database/migrations/" . $fileName);

            foreach ($stubVariables as $search => $replace) {
                if ($search === "dataType") {
                    $contents = str_replace('$' . $search, $replace, $contents);
                } else {
                    $contents = str_replace('$' . $search, "'$replace'", $contents);
                }
            }

            $time = time();

            File::put(database_path("/migrations/" . $time . "_update_" . $tableName . "_" . $fieldName . "_" . strtolower($constrainName) . ".php"), $contents);

            Artisan::call("migrate", [
                '--path' => "database/migrations/" . $time . "_update_" . $tableName . "_" . $fieldName . "_" . strtolower($constrainName) . ".php"
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return Constant::STATUS_TRUE;
    }
}
