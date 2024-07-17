<?php

namespace Vcian\LaravelDBAuditor\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\NamingRules;

trait Rules
{
    use NamingRules;

    /**
     * @var array
     */
    protected array $result;

    /**
     * Check table name rules
     * @return array
     */
    public function allTablesRules(): array
    {
        $checkTableStandard = Constant::ARRAY_DECLARATION; // array of table name and status
        $tableList = collect($this->getTableList())
            ->diff(config('db-auditor.skip_tables'))
            ->toArray();

        foreach ($tableList as $tableName) {
            $status = $this->checkStatus($tableName);
            $size = $this->getTableSize($tableName);
            $checkTableStandard[] = ["name" => $tableName, "status" => $status, "size" => $size];
        }

        return $checkTableStandard;
    }

    /**
     * Check Status for Tables and Fields
     * @param string $tableName
     * @return string
     */
    public function checkStatus(string $tableName): string
    {
        $status = Constant::STATUS_TRUE;
        $tableCheck = $this->checkRules($tableName, Constant::TABLE_RULES);
        if (!empty($tableCheck)) {
            $status = Constant::STATUS_FALSE;
        } else {
            $filedDetails = $this->fieldRules($tableName);
            foreach ($filedDetails as $field) {
                unset($field['suggestion']);
                unset($field['datatype']);
                if (!empty($field)) {
                    $status = Constant::STATUS_FALSE;
                }
            }
        }
        return $status;
    }

    /**
     * Check Rules for Fields and Tables
     * @param string $name
     * @param string|null $type
     * @return array
     */
    public function checkRules(string $name, string $type = null): array
    {
        $messages = Constant::ARRAY_DECLARATION;

        try {
            $this->setConventionName($name);
            $checkConvention = $this->nameConvention();
            $checkAlphabets = $this->nameHasAlphabetCharacterSet();

            if ($type === Constant::TABLE_RULES) {
                $checkLength = $this->nameHasFixLength();
                $checkNamePlural = $this->nameAlwaysPlural();

                if (!$checkLength) {
                    $messages[] = __('Lang::messages.standard.error_message.length');
                }

                if ($checkNamePlural !== Constant::STATUS_TRUE) {
                    $messages[] = __('Lang::messages.standard.error_message.plural') . "($checkNamePlural)";
                }
            }

            if ($checkAlphabets !== Constant::STATUS_TRUE) {
                $messages[] = __('Lang::messages.standard.error_message.alphabets') . "($checkAlphabets)";
            }

            if ($checkConvention !== Constant::STATUS_TRUE) {
                $messages[] = __('Lang::messages.standard.error_message.convention') . "($checkConvention)";
            }

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $messages;
    }

    /**
     * Check field rules
     * @param string $tableName
     * @return array
     */
    public function fieldRules(string $tableName): array
    {
        $checkFields = Constant::ARRAY_DECLARATION;
        try {
            $fields = $this->getFields($tableName);

            foreach ($fields as $field) {
                $checkFields[$field] = $this->checkRules($field, Constant::FIELD_RULES);
                $dataTypeDetails = $this->getFieldDataType($tableName, $field);
                $checkFields[$field]['datatype'] = $dataTypeDetails;

                if (connection_driver() === 'mysql' && $dataTypeDetails['data_type'] === Constant::DATATYPE_VARCHAR
                    && $dataTypeDetails['size'] <= Constant::DATATYPE_VARCHAR_SIZE
                ) {
                    $checkFields[$field]['suggestion'] = __('Lang::messages.standard.error_message.datatype_change');
                } elseif (connection_driver() === 'mysql') {
                    $checkFields[$field]['suggestion'] = __('Lang::messages.standard.error_message.datatype_change');
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $checkFields;
    }

    /**
     * Check rules for single table and check table exist or not
     * @param string $tableName
     * @return array|bool
     */
    public function tableRules(string $tableName): array|bool
    {
        $checkTableStatus = Constant::ARRAY_DECLARATION;

        try {
            if ($tableName && !$this->checkTableExist($tableName)) {
                return Constant::STATUS_FALSE;
            }

            $fields = $this->fieldRules($tableName);
            $tableComment = $this->checkRules($tableName, Constant::TABLE_RULES);
            $checkTableStatus = ["table" => $tableName, "table_comment" => $tableComment, "fields" => $fields];

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $checkTableStatus;
    }
}
