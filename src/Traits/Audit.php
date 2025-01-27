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

    public function generatePerformanceReport(): array
    {
        try {
            $report = [];
            
            $variables = DB::select("SHOW VARIABLES");
            $variablesMap = array_column($variables, 'Value', 'Variable_name');

            $report['innodb_buffer_pool_size'] = [
                'current' => $variablesMap['innodb_buffer_pool_size'],
                'suggestion' => $this->suggestInnoDBBufferPoolSize($variablesMap['innodb_buffer_pool_size'])
            ];

            $report['query_cache_size'] = ($variablesMap['have_query_cache'] === "YES") ? [
                'current' => $variablesMap['query_cache_size'],
                'suggestion' => $this->suggestQueryCacheSize($variablesMap['query_cache_size'])
            ] : [
                'current' => "DISABLES",
                'suggestion' => "To see query cache size, you need to enable query cache first."
            ];

            $report['max_connections'] = [
                'current' => $variablesMap['max_connections'],
                'suggestion' => $this->suggestMaxConnections($variablesMap['max_connections'])
            ];

            $report['innodb_log_file_size'] = [
                'current' => $variablesMap['innodb_log_file_size'],
                'suggestion' => $this->suggestInnoDBLogFileSize($variablesMap['innodb_log_file_size'])
            ];

            return $report;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return ['error' => 'Failed to generate database performance report'];
        }
    }

    private function suggestInnoDBBufferPoolSize($currentSize): string
    {
        $sizeInGB = $currentSize / (1024 * 1024 * 1024);
        if ($sizeInGB < 1) {
            return "Consider increasing to at least 1GB for better performance";
        } elseif ($sizeInGB > 70) {
            return "Current size is good. Ensure it doesn't exceed 70-80% of total RAM";
        }
        return "Current size seems appropriate";
    }

    private function suggestQueryCacheSize($currentSize): string
    {
        $sizeInMB = $currentSize / (1024 * 1024);
        if ($sizeInMB == 0) {
            return "Query cache is disabled. Consider enabling it for read-heavy workloads";
        } elseif ($sizeInMB > 256) {
            return "Consider reducing query cache size. Larger sizes can lead to overhead";
        }
        return "Current size seems appropriate";
    }

    private function suggestMaxConnections($currentValue): string
    {
        if ($currentValue < 100) {
            return "Consider increasing max_connections for better concurrency";
        } elseif ($currentValue > 1000) {
            return "High max_connections. Ensure server can handle this many connections";
        }
        return "Current value seems appropriate";
    }

    private function suggestInnoDBLogFileSize($currentSize): string
    {
        $sizeInMB = $currentSize / (1024 * 1024);
        if ($sizeInMB < 128) {
            return "Consider increasing innodb_log_file_size for better performance";
        } elseif ($sizeInMB > 2048) {
            return "Large log file size. Ensure it doesn't impact crash recovery time";
        }
        return "Current size seems appropriate";
    }


    /**
     * Check table is exist or not.
     * 
     * @param string $tableName
     * @return bool
     */
    public function isTableExist(string $tableName): bool
    {
        return $this->checkTableExist($tableName);
    }
}
