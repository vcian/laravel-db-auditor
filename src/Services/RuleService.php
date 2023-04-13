<?php

namespace Vcian\LaravelDBAuditor\Services;

use Exception;
use Illuminate\Support\Facades\DB;
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
        try {
            $query = 'SELECT ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024),2) AS `size` FROM information_schema.TABLES WHERE TABLE_SCHEMA = "' . env('DB_DATABASE') . '" AND TABLE_NAME = "' . $tableName . '" ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC';
            $result = DB::select($query);
            return $result[0]->size;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
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
                    $messages[] = 'Names should be not more than 64 characters.';
                }

                if (!$checkNamePlural) {
                    $messages[] = 'Use Table Name Plural.';
                }
            }

            if (!$checkSpace) {
                $messages[] = 'Using space between words is not advised. Please Use Underscore.';
            }

            if (!$checkAlphabets) {
                $messages[] = 'Numbers are not for names! Please use alphabets for name.';
            }


            if (!$checkLowerCase) {
                $messages[] = 'Use lowercase MYSQL is case sensitive.';
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $messages;
    }

    /**
     * Check rules for single table and check table exist or not
     * @param string $tableName
     * @return array
     */
    public function tableRules($tableName)
    {
        $checkTableStatus = Constant::ARRAY_DECLARATION;
        try {
            if ($tableName) {
                $tableExist = $this->dBConnectionService->checkTableExist($tableName);
                if (!$tableExist) {
                    return false;
                }
                $checkTableStatus[$tableName] = $this->checkRules($tableName, Constant::TABLE_RULES);
                $checkTableStatus[$tableName]['fields'] = $this->fieldRules($tableName);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $checkTableStatus;
    }
}
