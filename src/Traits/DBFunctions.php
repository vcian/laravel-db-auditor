<?php

namespace Vcian\LaravelDBAuditor\Traits;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Queries\DatabaseCharacterSetClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseEngineClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseFieldDetailsClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseSizeClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseTableClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseTableFieldIndexClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseTableFieldsClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseTableFieldTypeClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseTableSizeClass;

trait DBFunctions
{
    /**
     * @return string
     */

    /**
     * Get list of datatype of field and name of the field by table name
     * @param string $tableName
     * @return array $fields
     */
    public function getFields(string $tableName): array
    {
        $fields = new DatabaseTableFieldsClass($tableName);
        return $fields();
    }

    /**
     * Check Table exist or not in the database
     * @param string $tableName
     * @return bool
     */
    public function checkTableExist(string $tableName): bool
    {
        $tables = $this->getTableList();

        if (in_array($tableName, $tables)) {
            return Constant::STATUS_TRUE;
        }

        return Constant::STATUS_FALSE;
    }

    /**
     * Get Table List
     * @return array|string|int
     */
    public function getTableList(): array
    {
        $tableList = new DatabaseTableClass();
        return $tableList();
    }

    /**
     * Get field with type by table
     * @param string $tableName
     * @return array
     */
    public function getFieldsDetails(string $tableName): array
    {
        $fieldDetails = new DatabaseFieldDetailsClass($tableName);
        return $fieldDetails() ?? Constant::ARRAY_DECLARATION;
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function getTableSize(string $tableName): string
    {
        $size = new DatabaseTableSizeClass($tableName);
        return $size();
    }

    /**
     * Get Field Data Type
     * @param string $tableName
     * @param string $fieldName
     * @return array|bool
     */
    public function getFieldDataType(string $tableName, string $fieldName): array|bool
    {
        $fieldDataType = new DatabaseTableFieldTypeClass(
            $tableName,
            $fieldName
        );

        return $fieldDataType();
    }

    /**
     * Check Field Has Index Constraint
     * @param string $tableName
     * @param string $fieldName
     * @return bool
     */
    public function checkFieldHasIndex(string $tableName, string $fieldName): bool
    {
        $getIndex = new DatabaseTableFieldIndexClass($tableName, $fieldName);
        return $getIndex();
    }

    /**
     * @return string
     */
    public function getDatabaseSize(): string
    {
        $size = new DatabaseSizeClass();
        return $size();
    }

    /**
     * @return string
     */
    public function getDatabaseEngin(): string
    {
        $engine = new DatabaseEngineClass();
        return $engine();
    }

    /**
     * @return string
     */
    public function getCharacterSetName(): string
    {
        $characterSet = new DatabaseCharacterSetClass();
        return $characterSet();
    }
}
