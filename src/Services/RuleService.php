<?php

namespace Vcian\LaravelDBAuditor\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Vcian\LaravelDBAuditor\Constants\Constant;

class RuleService
{
    /**
     * @var array
     */
    protected array $result;

    /**
     * @param DBConnectionService $dBConnectionService
     * @param NamingRuleService $namingRuleService
     */
    public function __construct(
        protected DBConnectionService $dBConnectionService,
        protected NamingRuleService   $namingRuleService)
    {
    }

    /**
     * Get Table List
     * @return array
     */
    public function getTableList(): array
    {
        return $this->dBConnectionService->getTableList();
    }

    /**
     * Check table name rules
     * @return array
     */
    public function tablesRule(): array
    {
        $checkTableStandard = Constant::ARRAY_DECLARATION;
        try {
            $tableList = $this->dBConnectionService->getTableList();
            foreach ($tableList as $tableName) {
                $status = $this->checkStatus($tableName);
                $size = $this->getTableSize($tableName);
                $checkTableStandard[] = ["name" => $tableName, "status" => $status, "size" => $size];
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
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
            $checkLowerCase = $this->namingRuleService->nameOnlyLowerCase($name);
            $checkSpace = $this->namingRuleService->nameHasNoSpace($name);
            $checkAlphabets = $this->namingRuleService->nameHasOnlyAlphabets($name);

            if ($type === Constant::TABLE_RULES) {
                $checkLength = $this->namingRuleService->nameHasFixLength($name);
                $checkNamePlural = $this->namingRuleService->nameAlwaysPlural($name);

                if (!$checkLength) {
                    $messages[] = __('Lang::messages.standard.error_message.length');
                }

                if ($checkNamePlural !== Constant::STATUS_TRUE) {
                    $messages[] = __('Lang::messages.standard.error_message.plural') . "($checkNamePlural)";
                }
            }

            if ($checkSpace !== Constant::STATUS_TRUE) {
                $messages[] = __('Lang::messages.standard.error_message.space') . "($checkSpace)";
            }

            if ($checkAlphabets !== Constant::STATUS_TRUE) {
                $messages[] = __('Lang::messages.standard.error_message.alphabets') . "($checkAlphabets)";
            }

            if ($checkLowerCase !== Constant::STATUS_TRUE) {
                $messages[] = __('Lang::messages.standard.error_message.lowercase') . "($checkLowerCase)";
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
            $fields = $this->dBConnectionService->getFields($tableName);

            foreach ($fields as $field) {
                $checkFields[$field] = $this->checkRules($field, Constant::FIELD_RULES);
                $dataTypeDetails = $this->dBConnectionService->getFieldDataType($tableName, $field);

                if ($dataTypeDetails['data_type'] === Constant::DATATYPE_VARCHAR && $dataTypeDetails['size'] <= Constant::DATATYPE_VARCHAR_SIZE) {
                    $checkFields[$field][] = __('Lang::messages.standard.error_message.datatype_change');
                }
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $checkFields;
    }

    /**
     * Get Table Size
     * @param string $tableName
     * @return string
     */
    public function getTableSize(string $tableName): string
    {
        return $this->dBConnectionService->getTableSize($tableName);
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
            if ($tableName) {
                $tableExist = $this->dBConnectionService->checkTableExist($tableName);

                if (!$tableExist) {
                    return Constant::STATUS_FALSE;
                }

                $fields = $this->fieldRules($tableName);
                $tableComment = $this->checkRules($tableName, Constant::TABLE_RULES);
                $checkTableStatus = ["table" => $tableName, "table_comment" => $tableComment, "fields" => $fields];
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $checkTableStatus;
    }
}
