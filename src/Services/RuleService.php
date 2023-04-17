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
    protected $result = Constant::ARRAY_DECLARATION;

    /**
     * @param DBConnectionService
     * @param NamingRuleService
     * @param TableReadService
     */
    public function __construct(
        protected DBConnectionService $dBConnectionService,
        protected NamingRuleService $namingRuleService
    ) {
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
                array_push($checkTableStandard, ["name" => $tableName, "status" => $status, "size" => $size]);
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
            $filedsDetails = $this->fieldRules($tableName);
            foreach ($filedsDetails as $field) {
                if (!empty($field)) {
                    $status = Constant::STATUS_FALSE;
                }
            }
        }
        return $status;
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
     * Check field rules
     * @param string $tableName
     * @return array
     */
    public function fieldRules($tableName): array
    {
        $checkFields = Constant::ARRAY_DECLARATION;
        try {
            $filedsDetails = $this->dBConnectionService->getFields($tableName);
            foreach ($filedsDetails as $field) {
                $checkFields[$field] = $this->checkRules($field, Constant::FIELD_RULES);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $checkFields;
    }

    /**
     * Check Rules for Fields and Tables
     * @param string $name
     * @param string $type
     * @return array
     */
    public function checkRules($name, $type = null): array
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
                    $messages[] = __('Lang::messages.standard.error_messages.length');
                }

                if (!$checkNamePlural) {
                    $messages[] = __('Lang::messages.standard.error_messages.plural');
                }
            }

            if (!$checkSpace) {
                $messages[] = __('Lang::messages.standard.error_messages.space');
            }

            if (!$checkAlphabets) {
                $messages[] = __('Lang::messages.standard.error_messages.alphabets');
            }


            if (!$checkLowerCase) {
                $messages[] = __('Lang::messages.standard.error_messages.lowercase');
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $messages;
    }

    /**
     * Check rules for single table and check table exist or not
     * @param string $tableName
     * @return mixed
     */
    public function tableRules($tableName): mixed
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
                $checkTableStatus = ["table" => $tableName,  "table_comment" => $tableComment, "fields" => $fields];
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $checkTableStatus;
    }
}
