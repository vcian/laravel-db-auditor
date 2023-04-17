<?php

namespace Vcian\LaravelDBAuditor\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use function Termwind\{render};

class AuditService
{
    protected $results = Constant::ARRAY_DECLARATION;

    protected $tableList;

    public function __construct(protected DBConnectionService $dBConnectionService)
    {
        $this->tableList = $this->dBConnectionService->getTableList();
    }

    /**
     * Get All Table List
     * @return array
     */
    public function getTablesList() : array
    {
        return $this->tableList;
    }

    /**
     * Get Table Fields
     * @param string $tableName
     * @return array
     */
    public function getTableFields(string $tableName) : array
    {
        try {
            $fields = $this->dBConnectionService->getFieldsDetails($tableName);
            return $fields;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Get Table Size
     * @param string $tableName
     * @return string $size 
     */
    public function getTableSize(string $tableName)
    {
        $tableSize = $this->dBConnectionService->getTableSize($tableName);
        return $tableSize;
    }

    /**
     * Get Fields which has no constrain
     * @param string $tableName
     */
    public function getNoConstrainFields($tableName)
    {
        $fields = Constant::ARRAY_DECLARATION;
        try {
            $fieldList = DB::select("SELECT * FROM `INFORMATION_SCHEMA`.`COLUMNS` 
            WHERE `TABLE_SCHEMA`= '".env('DB_DATABASE')."' AND `TABLE_NAME`= '".$tableName."' AND `COLUMN_KEY` = '' ");

            foreach($fieldList as $field) {
                if(str_contains($field->DATA_TYPE, "int")) {
                    $fields['integer'][] = $field->COLUMN_NAME;  
                } else {
                    $fields['mix'][] = $field->COLUMN_NAME;
                }
            }

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $fields;
    }

    /**
     * Get Constrain List
     * @return array
     */
    public function getConstrainList($tableName, $fields)
    {
        $constrainList = Constant::ARRAY_DECLARATION;
        if(isset($fields['integer']) && !empty($fields['integer']))  {
            array_push($constrainList, Constant::CONSTRAIN_FOREIGN_KEY);

            $primary = $this->getConstrainField($tableName, Constant::CONSTRAIN_PRIMARY_KEY);
            if(empty($primary)) {
                array_push($constrainList, Constant::CONSTRAIN_PRIMARY_KEY);
            }
        }
        array_push($constrainList, Constant::CONSTRAIN_INDEX_KEY);
        array_push($constrainList, Constant::CONSTRAIN_UNIQUE_KEY);
        return $constrainList;
    }

    /**
     * Check Table Has Value
     */
    public function tableHasValue(string $tableName)
    {
        try {
            $tableContainValue = DB::select("Select * from ".$tableName."");
            if($tableContainValue) {
                return true;
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return false;
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
                    $this->getConstrainField($tableName, $input);
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
    public function getConstrainField(string $tableName, string $input)
    {
        try {
            $constrainFields = Constant::ARRAY_DECLARATION;
            if (!$this->dBConnectionService->checkTableExist($tableName)) {
                return [];
            }

            $result = DB::select("SHOW KEYS FROM {$tableName} WHERE Key_name LIKE '%" . strtolower($input) . "%'");

            if ($input == Constant::CONSTRAIN_FOREIGN_KEY) {
                $foreignFieldDetails = $this->getForeignKeyDetails($tableName);
                return $foreignFieldDetails;
            }

            if ($result) {
                foreach ($result as $value) {
                    array_push($constrainFields, $value->Column_name);
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $constrainFields;
    }

    /**
     * Add Foreign Key
     * @param string
     * @return array
     */
    public function getForeignKeyDetails(string $tableName)
    {
        $foreignFieldDetails = Constant::ARRAY_DECLARATION;
        try {
            $resultForeignKey = DB::select("SELECT i.TABLE_SCHEMA, i.TABLE_NAME, i.CONSTRAINT_TYPE,k.COLUMN_NAME, i.CONSTRAINT_NAME,
            k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME FROM information_schema.TABLE_CONSTRAINTS i
            LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
            WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY' AND i.TABLE_SCHEMA = '" . env('DB_DATABASE') . "' AND i.TABLE_NAME = '" . $tableName . "'");
            
            if ($resultForeignKey) {
                foreach ($resultForeignKey as $value) {
                    array_push($foreignFieldDetails, ["colum_name" => $value->COLUMN_NAME, 
                                                    "foreign_table_name" => $value->REFERENCED_TABLE_NAME, 
                                                    "foreign_colum_name" => $value->REFERENCED_COLUMN_NAME]);         
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $foreignFieldDetails;
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
        $fieldType = $this->dBConnectionService->getFieldsDetails($tableName);
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
                $this->migrateConstrain(Constant::PRIMARY_FILE_NAME, $constrain, $table, $field);
            } elseif ($constrain == Constant::CONSTRAIN_INDEX_KEY ) {
               $this->migrateConstrain(Constant::INDEX_FILE_NAME, $constrain, $table, $field);
            } elseif ($constrain == Constant::CONSTRAIN_UNIQUE_KEY) {
                $this->migrateConstrain(Constant::UNIQUE_FILE_NAME, $constrain, $table, $field);
            } elseif ($constrain == Constant::CONSTRAIN_FOREIGN_KEY) {
                $this->migrateConstrain(Constant::FOREIGN_FILE_NAME, $constrain, $table, $field, $referenceField, $referenceTableName);
            } else {
                return false;
            }
            
            
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        
        return true;
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

    /**
     * Dynamic Migration
     */
    public function migrateConstrain($fileName, $constrainName, $tableName, $fieldName, $referenceField = null, $referenceTableName = null)
    {
        try {
            
            $dataType = $this->getFieldDataType($tableName, $fieldName);

            if($dataType) {
                if($dataType === "varchar") {
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

            $contents = file_get_contents(__DIR__ . "/../Database/migrations/" .$fileName);

            foreach ($stubVariables as $search => $replace)
            {
                if($search === "dataType") {
                    $contents = str_replace('$'.$search , $replace, $contents);
                } else {
                    $contents = str_replace('$'.$search , "'$replace'", $contents);
                }
                
            }
            $time = time();

            File::put(database_path("/migrations/".$time."_update_".$tableName."_".$fieldName."_".strtolower($constrainName).".php"), $contents);
        
            $data = Artisan::call("migrate", [
                '--force' => true,
                '--path' => "database/migrations/".$time."_update_".$tableName."_".$fieldName."_".strtolower($constrainName).".php"
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
     */
    public function getFieldDataType(string $tableName, string $fieldName)
    {
        try {
            
            $dataType = DB::select("SELECT `DATA_TYPE` FROM `INFORMATION_SCHEMA`.`COLUMNS` 
            WHERE `TABLE_SCHEMA`= '".env('DB_DATABASE')."' AND `TABLE_NAME`= '".$tableName."' AND `COLUMN_NAME` = '".$fieldName."' ");
    
            if(isset($dataType[0]->DATA_TYPE) && $dataType[0]->DATA_TYPE !== null) {
                return $dataType[0]->DATA_TYPE;
            } else {
                return false;
            }
            

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
