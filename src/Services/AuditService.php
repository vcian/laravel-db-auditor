<?php

namespace Vcian\LaravelDBAuditor\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;

class AuditService
{
    protected $results = Constant::ARRAY_DECLARATION;

    public function __construct(protected DBConnectionService $dBConnectionService)
    {
        // 
    }

    /**
     * Get All Table List
     * @return array
     */
    public function getTablesList(): array
    {
        $tableList = $this->dBConnectionService->getTableList();
        return $tableList;
    }

    /**
     * Get Table Fields
     * @param string $tableName
     * @return array
     */
    public function getTableFields(string $tableName): array
    {
        $fields = $this->dBConnectionService->getFieldsDetails($tableName);
        return $fields;
    }

    /**
     * Get Table Size
     * @param string $tableName
     * @return string $size 
     * @return string
     */
    public function getTableSize(string $tableName): string
    {
        $tableSize = $this->dBConnectionService->getTableSize($tableName);
        return $tableSize;
    }

    /**
     * Get fields which has no constraint
     * @param string $tableName
     * @return array
     */
    public function getNoConstraintFields(string $tableName): array
    {
        $fields = Constant::ARRAY_DECLARATION;
        try {
            $fieldList = DB::select("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS` 
            WHERE `TABLE_SCHEMA`= '" . env('DB_DATABASE') . "' AND `TABLE_NAME`= '" . $tableName . "' AND `COLUMN_KEY` = '' ");

            foreach ($fieldList as $field) {
                if ((!str_contains($field->DATA_TYPE, "timestamp")) && (!str_contains($field->DATA_TYPE, "date")) && (!str_contains($field->DATA_TYPE, "datetime"))) {
                    if (str_contains($field->DATA_TYPE, "int")) {
                        $fields['integer'][] = $field->COLUMN_NAME;
                    } else {
                        $fields['mix'][] = $field->COLUMN_NAME;
                    }
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fields;
    }

    /**
     * Get Constraint List
     * @param string $tableName
     * @param array $fields
     * @return array
     */
    public function getConstraintList(string $tableName, array $fields): array
    {
        $constrainList = Constant::ARRAY_DECLARATION;

        if (isset($fields['integer']) && !empty($fields['integer'])) {
            array_push($constrainList, Constant::CONSTRAINT_FOREIGN_KEY);

            $primary = $this->getConstraintField($tableName, Constant::CONSTRAINT_PRIMARY_KEY);
            if (empty($primary)) {
                array_push($constrainList, Constant::CONSTRAINT_PRIMARY_KEY);
            }
        }
        array_push($constrainList, Constant::CONSTRAINT_INDEX_KEY);
        array_push($constrainList, Constant::CONSTRAINT_UNIQUE_KEY);

        return $constrainList;
    }

    /**
     * Check Table Has Value
     * @param string $tableName
     * @return bool
     */
    public function tableHasValue(string $tableName): bool
    {
        try {
            $tableContainValue = DB::select("Select * from " . $tableName . "");
            if ($tableContainValue) {
                return Constant::STATUS_TRUE;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return Constant::STATUS_FALSE;
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
            if (!$this->dBConnectionService->checkTableExist($tableName)) {
                return [];
            }

            $result = DB::select("SHOW KEYS FROM {$tableName} WHERE Key_name LIKE '%" . strtolower($input) . "%'");

            if ($input == Constant::CONSTRAINT_FOREIGN_KEY) {
                $foreignFieldDetails = $this->getForeignKeyDetails($tableName);
                return $foreignFieldDetails;
            }

            if ($result) {
                foreach ($result as $value) {
                    array_push($constraintFields, $value->Column_name);
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
            WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY' AND i.TABLE_SCHEMA = '" . env('DB_DATABASE') . "' AND i.TABLE_NAME = '" . $tableName . "'");

            if ($resultForeignKey) {
                foreach ($resultForeignKey as $value) {
                    array_push($foreignFieldDetails, [
                        "colum_name" => $value->COLUMN_NAME,
                        "foreign_table_name" => $value->REFERENCED_TABLE_NAME,
                        "foreign_colum_name" => $value->REFERENCED_COLUMN_NAME
                    ]);
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $foreignFieldDetails;
    }

    /**
     * Add constraint to the field
     * @param string $table
     * @param string $field
     * @param string $constraint
     * @param string $referenceTableName
     * @param string $referenceField
     * @return bool
     */
    public function addConstraint(
        string $table,
        string $field,
        string $constraint,
        string $referenceTableName = null,
        string $referenceField = null
    ): bool {
        try {
            if ($constraint == Constant::CONSTRAINT_PRIMARY_KEY) {
                $this->migrateConstrain(Constant::PRIMARY_FILE_NAME, $constraint, $table, $field);
            } elseif ($constraint == Constant::CONSTRAINT_INDEX_KEY) {
                $this->migrateConstrain(Constant::INDEX_FILE_NAME, $constraint, $table, $field);
            } elseif ($constraint == Constant::CONSTRAINT_UNIQUE_KEY) {
                $this->migrateConstrain(Constant::UNIQUE_FILE_NAME, $constraint, $table, $field);
            } elseif ($constraint == Constant::CONSTRAINT_FOREIGN_KEY) {
                $this->migrateConstrain(Constant::FOREIGN_FILE_NAME, $constraint, $table, $field, $referenceField, $referenceTableName);
            } else {
                return false;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return true;
    }

    /**
     * Dynamic Migration
     * @param string $fileName
     * @param string $constrainName
     * @param string $tableName
     * @param string $fieldName
     * @param string $referenceField
     * @param string $referenceTableName
     * @return bool
     */
    public function migrateConstrain(
        string $fileName,
        string $constrainName,
        string $tableName,
        string $fieldName,
        string $referenceField = null,
        string $referenceTableName = null
    ): bool {
        try {
            $dataType = $this->getFieldDataType($tableName, $fieldName);

            if ($dataType) {
                if ($dataType === "varchar") {
                    $fieldDataType = "string";
                } else {
                    $fieldDataType = $dataType;
                }
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
                '--force' => true,
                '--path' => "database/migrations/" . $time . "_update_" . $tableName . "_" . $fieldName . "_" . strtolower($constrainName) . ".php"
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return true;
    }

    /**
     * Get Field Data Type
     * @param string
     * @param string
     * @return mixed
     */
    public function getFieldDataType(string $tableName, string $fieldName): mixed
    {
        try {
            $dataType = DB::select("SELECT `DATA_TYPE` FROM `INFORMATION_SCHEMA`.`COLUMNS` 
            WHERE `TABLE_SCHEMA`= '" . env('DB_DATABASE') . "' AND `TABLE_NAME`= '" . $tableName . "' AND `COLUMN_NAME` = '" . $fieldName . "' ");

            if (isset($dataType[0]->DATA_TYPE) && $dataType[0]->DATA_TYPE !== null) {
                return $dataType[0]->DATA_TYPE;
            } else {
                return Constant::STATUS_FALSE;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
