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

    protected $tableList;

    public function __construct(protected DBConnectionService $dBConnectionService)
    {
        $this->tableList = $this->dBConnectionService->getTableList();
    }

    /**
     * Get All the Constrains list with table name and column.
     * @param string $input
     * @return array
     */
    public function getList($input): array
    {
        try {
            if ($this->tableList) {
                foreach ($this->tableList as $tableName) {
                    $this->checkConstrain($tableName, $input);
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $this->results;
    }

    /**
     * Check Constrain
     * @param string $tableName
     * @param string $input
     * @return array
     */
    public function checkConstrain(string $tableName, string $input): array
    {
        try {
            if (!$this->dBConnectionService->checkTableExist($tableName)) {
                return [];
            }

            if ($input === Constant::CONSTRAIN_ALL_KEY) {
                $result = DB::select("SHOW KEYS FROM {$tableName}");
                $this->checkForeignKeyData($tableName);
            } else {
                $result = DB::select("SHOW KEYS FROM {$tableName} WHERE Key_name LIKE '%" . strtolower($input) . "%'");
            }

            if ($input == Constant::CONSTRAIN_FOREIGN_KEY) {
                $this->checkForeignKeyData($tableName);
            }

            if ($result) {
                foreach ($result as $value) {
                    array_push($this->results, [$tableName, $value->Column_name, $value->Key_name]);
                }
            } else {
                array_push($this->results, [$tableName, Constant::DASH, Constant::DASH]);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->results;
    }

    /**
     * Add Foreign Key
     * @param string
     * @return void
     */
    public function checkForeignKeyData(string $tableName): void
    {
        try {
            $resultForeignKey = DB::select("SELECT i.TABLE_SCHEMA, i.TABLE_NAME, i.CONSTRAINT_TYPE,k.COLUMN_NAME, i.CONSTRAINT_NAME,
            k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME FROM information_schema.TABLE_CONSTRAINTS i
            LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
            WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY' AND i.TABLE_SCHEMA = '" . config("Database.connections.mysql.Database") . "' AND i.TABLE_NAME = '" . $tableName . "'");

            if ($resultForeignKey) {
                foreach ($resultForeignKey as $value) {
                    array_push($this->results, [$value->TABLE_NAME, $value->Constant::CONSTRAIN_FOREIGN_KEY, $value->REFERENCED_TABLE_NAME, $value->REFERENCED_COLUMN_NAME]);
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Check table Exist or not and value
     * @param string $tableName
     * @param string $input
     */
    public function getTableList(string $tableName, string $input)
    {

        try {

            $checkTableStatus = Constant::ARRAY_DECLARATION;
            if (in_array($checkTableStatus, $this->tableList)) {
                return true;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return true;
    }

    /**
     * Get Field List By User Input
     */
    public function getFieldByUserInput(string $tableName, string $userInput)
    {
        $fieldList = Constant::ARRAY_DECLARATION;
        if ($userInput === Constant::CONSTRAIN_PRIMARY_KEY && $this->checkTableHasPrimaryKey($tableName)) {
            return $fieldList;
        }
        $fieldType = $this->dBConnectionService->getFieldWithType($tableName);
        if ($fieldType) {
            foreach ($fieldType as $field) {
                if (str_contains($field->Type, "int")) {
                    if (!$this->getConstrainFields($tableName, $field->Field)) {
                        array_push($fieldList, $field->Field);
                    }
                }
            }
        }
        return $fieldList;
    }

    /**
     * Add Constrain
     */
    public function addConstrain($table, $field, $constrain, $referenceTableName = null, $referenceField = null)
    {
        try {
            if ($constrain == Constant::CONSTRAIN_PRIMARY_KEY) {
                $this->migrateConstrain(Constant::PRIMARY_FILE_NAME, $table, $field, $referenceField, $referenceTableName);
            } elseif ($constrain == Constant::CONSTRAIN_INDEX_KEY ) {
                $this->migrateConstrain(Constant::INDEX_FILE_NAME, $table, $field, $referenceField, $referenceTableName);
            } elseif ($constrain == Constant::CONSTRAIN_UNIQUE_KEY) {
                $this->migrateConstrain(Constant::UNIQUE_FILE_NAME, $table, $field, $referenceField, $referenceTableName);
            } elseif ($constrain == Constant::CONSTRAIN_FOREIGN_KEY) {
                if (!$this->dBConnectionService->checkTableExist($referenceTableName)) {
                    return false;
                }
                $this->migrateConstrain(Constant::FOREIGN_FILE_NAME, $table, $field, $referenceField, $referenceTableName);
            }

            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Field Exist
     */
    public function getConstrainFields($table, $fieldName)
    {
        $result = DB::select("SHOW KEYS FROM {$table} WHERE Column_name LIKE '%" . strtolower($fieldName) . "%'");
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * Check Table Has Primary
     */
    public function checkTableHasPrimaryKey(string $tableName)
    {
        $result = DB::select("SHOW KEYS FROM {$tableName} WHERE Key_name LIKE '%primary%'");
        if ($result) {
            return true;
        }
        return false;
    }

    public function migrateConstrain($fileName, $tableName, $fieldName, $referenceField = null, $referenceTableName = null)
    {
        try {
            $stubVariables = [
                "table_name" => $tableName,
                "field_name" => $fieldName,
                "reference_field" => $referenceField,
                "reference_table" => $referenceTableName
            ];

            $contents = file_get_contents(__DIR__ . "/../Database/migrations" .Constant::INDEX_FILE_NAME);

            foreach ($stubVariables as $search => $replace)
            {
                $contents = str_replace('$'.$search , "'$replace'", $contents);
            }

            File::put(database_path("/migrations/update_".$tableName."_".$fieldName."_index.php"), $contents);

            Artisan::call("migrate", [
                '--path' => "Database/migrations/update_".$tableName."_".$fieldName."_index.php"
            ]);

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
